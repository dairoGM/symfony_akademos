<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\Solicitud;
use App\Entity\Personal\Persona;
use App\Entity\Evaluacion\Comision;
use App\Entity\Evaluacion\MiembrosComision;
use App\Form\Evaluacion\ComisionType;
use App\Repository\Evaluacion\EstadoSolicitudRepository;
use App\Repository\Evaluacion\SolicitudRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Evaluacion\ComisionRepository;
use App\Repository\Evaluacion\MiembrosComisionRepository;
use App\Repository\Evaluacion\RolComisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/comision")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_COMISION_EVALUADORA")
 */
class ComisionController extends AbstractController
{

    /**
     * @Route("/", name="app_comision_evaluadora_index", methods={"GET"})
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function index(Request $request, ComisionRepository $comisionRepository, MiembrosComisionRepository $miembrosComisionRepository)
    {
        $request->getSession()->remove('array_personas_asignadas');
        $request->getSession()->remove('idSolicitud');
        $registros = $comisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        $response = [];
        if (is_array($registros) && count($registros) > 0) {
            foreach ($registros as $value) {
                $value->miembros = $miembrosComisionRepository->findBy(['comision' => $value->getId()]);
                $response[] = $value;
            }
        }
        return $this->render('modules/evaluacion/comision/index.html.twig', [
            'registros' => $response,
        ]);
    }

    /**
     * @Route("/registrar", name="app_comision_evaluadora_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ComisionRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function registrar(Request $request, EntityManagerInterface $em, EstadoSolicitudRepository $estadoSolicitudRepository, SolicitudRepository $solicitudRepository, ComisionRepository $comisionRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {

        try {
            $idSolicitudParam = $request->query->get('id');
            if (!empty($idSolicitudParam)) {
                $request->getSession()->set('idSolicitud', $idSolicitudParam);
            }
            $idSolicitud = $request->getSession()->get('idSolicitud');


            $comisionEntity = new Comision();
            if (!empty($idSolicitud)) {
                $nombre = null;
                $solicitud = $solicitudRepository->find($idSolicitud);
                if ($solicitud->getTipoSolicitud() == 'institucion') {
                    $nombre = $solicitud->getInstitucion()->getEstructura()->getSiglas();
                } else if ($solicitud->getTipoSolicitud() == 'programa_pregrado') {
                    $prog = $solicitud->getProgramaPregrado()->getNombre();
                    $nombre = $prog . ", " . $solicitud->getProgramaPregrado()->getCentroRector()->getSiglas();
                } else if ($solicitud->getTipoSolicitud() == 'programa_posgrado') {
                    $prog = $solicitud->getProgramaPosgrado()->getNombre();
                    $nombre = $prog . ", " . $solicitud->getProgramaPosgrado()->getUniversidad()->getSiglas();
                }
                $comisionEntity->setNombre('Comisión evaluadora de: ' . $nombre);
            }

            $form = $this->createForm(ComisionType::class, $comisionEntity, ['action' => 'registrar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() /*&& $form->isValid()*/) {

                if ($request->getSession()->has('idSolicitud')) {
                    $solicitud = $solicitudRepository->find($request->getSession()->get('idSolicitud'));
                    $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_asignada_comision')));
                    $comisionEntity->setSolicitud($solicitud);
                }

                $comisionRepository->add($comisionEntity, true);

                if ($request->getSession()->has('array_personas_asignadas')) {
                    foreach ($request->getSession()->get('array_personas_asignadas') as $value) {
                        $miembroComisionEntity = new MiembrosComision();
                        $miembroComisionEntity->setMiembro($personaRepository->find($value['id_persona']));
                        $miembroComisionEntity->setRolComision($rolComisionRepository->find($value['id_rol']));
                        $miembroComisionEntity->setComision($comisionEntity);
                        $em->persist($miembroComisionEntity);
                    }
                    $em->flush();
                }


//                $request->getSession()->remove('idSolicitud');
                $request->getSession()->remove('array_personas_asignadas');
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                if (empty($idSolicitud)) {
                    $route = 'app_comision_evaluadora_index';
                } else {
                    $route = 'app_plan_anual_evaluacion_index';
                }

                return $this->redirectToRoute("$route", [], Response::HTTP_SEE_OTHER);
            }

            $personasSeleccionadas = $request->getSession()->has('array_personas_asignadas') ? $request->getSession()->get('array_personas_asignadas') : [];
            $asignadas = [];
            $arrayIdAsignados = [];
            if (count($personasSeleccionadas) > 0) {
                foreach ($personasSeleccionadas as $value) {
                    $item = $personaRepository->find($value['id_persona']);
                    $item->rolComision = $value['nombre_rol'];
                    $asignadas[] = $item;
                    $arrayIdAsignados[] = $value['id_persona'];
                }
            }

            return $this->render('modules/evaluacion/comision/new.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolComision' => $rolComisionRepository->findBy(['activo' => true], ['nombre' => 'asc']),
                'idSolicitud' => $idSolicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_evaluadora_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona", name="app_comision_evaluadora_asociar_persona_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public
    function asociarPersona(Request $request)
    {
        try {
            $arrayIds = $request->request->get('arrayId');

            if (is_array($arrayIds)) {
                if ($request->getSession()->has('array_personas_asignadas')) {
                    $result = array_merge($request->getSession()->get('array_personas_asignadas'), $arrayIds);
                } else {
                    $result = $arrayIds;
                }
                $request->getSession()->set('array_personas_asignadas', $result);
            }
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }


    /**
     * @Route("/{id}/eliminar_persona_asociada", name="app_comision_evaluadora_eliminar_persona_asignada_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param Persona $persona
     * @return Response
     */
    public
    function eliminarPersonaAsociada(Request $request, Persona $persona)
    {
        try {
            $nuevoArray = [];
            foreach ($request->getSession()->get('array_personas_asignadas') as $value) {
                if ($value['id_persona'] != $persona->getId()) {
                    $nuevoArray[] = $value;
                }
            }
            $request->getSession()->set('array_personas_asignadas', $nuevoArray);
            $params = [];
            if ($request->getSession()->has('idSolicitud')) {
                $params = ['id' => $request->getSession()->get('idSolicitud')];
            }
            return $this->redirectToRoute('app_comision_evaluadora_registrar', $params, Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/modificar", name="app_comision_evaluadora_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @param Comision $comision
     * @param ComisionRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public
    function modificar(Request $request, MiembrosComisionRepository $miembrosComisionRepository, Comision $comision, ComisionRepository $comisionRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(ComisionType::class, $comision, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comisionRepository->edit($comision);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_evaluadora_index', [], Response::HTTP_SEE_OTHER);
            }
            $request->getSession()->set('idComision', $comision->getId());
            $arrayIdAsignados = [];
            $asignadas = [];
            $temp = $miembrosComisionRepository->findBy(['comision' => $comision->getId()]);
            foreach ($temp as $value) {
                $arrayIdAsignados[] = $value->getMiembro()->getId();
                $item = $value->getMiembro();
                $item->rolComision = $value->getRolComision()->getNombre();
                $asignadas[] = $item;
            }


            return $this->render('modules/evaluacion/comision/edit.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolComision' => $rolComisionRepository->findBy(['activo' => true], ['nombre' => 'asc']),
                'comision' => $comision
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_evaluadora_modificar', ['id' => $comision->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona_modificar", name="app_comision_evaluadora_asociar_persona_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param ComisionRepository $comisionRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public
    function asociarPersonaModificar(Request $request, PersonaRepository $personaRepository, RolComisionRepository $rolComisionRepository, ComisionRepository $comisionRepository, EntityManagerInterface $em)
    {
        try {
            $arrayIds = $request->request->get('arrayId');
            if (is_array($arrayIds)) {
                foreach ($arrayIds as $value) {
                    $miembroComisionEntity = new MiembrosComision();
                    $miembroComisionEntity->setMiembro($personaRepository->find($value['id_persona']));
                    $miembroComisionEntity->setRolComision($rolComisionRepository->find($value['id_rol']));
                    $miembroComisionEntity->setComision($comisionRepository->find($request->getSession()->get('idComision')));
                    $em->persist($miembroComisionEntity);
                }
                $em->flush();
            }
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/eliminar_persona_asignada_modificar", name="app_comision_evaluadora_eliminar_persona_asignada_modificar", methods={"GET"})
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @return Response
     */
    public
    function eliminarPersonaAsignadaModificar(Request $request, Persona $persona, PersonaRepository $personaRepository, MiembrosComisionRepository $miembrosComisionRepository)
    {
        try {
            $comision = $request->getSession()->get('idComision');
            if ($personaRepository->find($persona->getId()) instanceof Persona) {
                $miembro = $miembrosComisionRepository->findOneBy(['miembro' => $persona->getId(), 'comision' => $comision]);
                $miembrosComisionRepository->remove($miembro, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_evaluadora_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_evaluadora_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_evaluadora_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_comision_evaluadora_detail", methods={"GET", "POST"})
     * @param Comision $comision
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @return Response
     */
    public
    function detail(Comision $comision, MiembrosComisionRepository $miembrosComisionRepository)
    {
        return $this->render('modules/evaluacion/comision/detail.html.twig', [
            'item' => $comision,
            'asignadas' => $miembrosComisionRepository->findBy(['comision' => $comision->getId()]),
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_comision_evaluadora_eliminar", methods={"GET"})
     * @param Request $request
     * @param Comision $comision
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public
    function eliminar(Request $request, Comision $comision, ComisionRepository $comisionRepository)
    {
        try {
            if ($comisionRepository->find($comision) instanceof Comision) {
                $comisionRepository->remove($comision, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_evaluadora_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_evaluadora_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_evaluadora_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
