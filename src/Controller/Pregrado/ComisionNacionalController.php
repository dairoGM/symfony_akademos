<?php

namespace App\Controller\Pregrado;

use App\Entity\Personal\Persona;
use App\Entity\Pregrado\MiembrosComision;
use App\Entity\Pregrado\ComisionNacional;
use App\Entity\Pregrado\MiembrosComisionNacional;
use App\Form\Pregrado\ComisionNacionalType;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\MiembrosComisionRepository;
use App\Repository\Pregrado\ComisionNacionalRepository;
use App\Repository\Pregrado\MiembrosComisionNacionalRepository;
use App\Repository\Postgrado\RolComisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/comision_nacional")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_COMNACION")
 */
class ComisionNacionalController extends AbstractController
{

    /**
     * @Route("/", name="app_comision_nacional_index", methods={"GET"})
     * @param Request $request
     * @param ComisionNacionalRepository $comisionRepository
     * @return Response
     */
    public function index(Request $request, ComisionNacionalRepository $comisionRepository, MiembrosComisionNacionalRepository $miembrosComisionRepository)
    {
        $request->getSession()->remove('array_personas_asignadas');
        $registros = $comisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        $response = [];
        if (is_array($registros) && count($registros) > 0){
            foreach ($registros as $value){
                $value->miembros = $miembrosComisionRepository->findBy(['comision' => $value->getId()]);
                $response[] = $value;
            }
        }
        return $this->render('modules/pregrado/comision_nacional/index.html.twig', [
            'registros' => $response,
        ]);
    }

    /**
     * @Route("/registrar", name="app_comision_nacional_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ComisionNacionalRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function registrar(Request $request, EntityManagerInterface $em, ComisionNacionalRepository $comisionRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $comisionEntity = new ComisionNacional();
            $form = $this->createForm(ComisionNacionalType::class, $comisionEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comisionRepository->add($comisionEntity, true);

                if ($request->getSession()->has('array_personas_asignadas')) {
                    foreach ($request->getSession()->get('array_personas_asignadas') as $value) {
                        $miembroComisionEntity = new MiembrosComisionNacional();
                        $miembroComisionEntity->setMiembro($personaRepository->find($value['id_persona']));
                        $miembroComisionEntity->setRolComision($rolComisionRepository->find($value['id_rol']));
                        $miembroComisionEntity->setComision($comisionEntity);
                        $em->persist($miembroComisionEntity);
                    }
                    $em->flush();
                }


                $request->getSession()->remove('array_personas_asignadas');
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_nacional_index', [], Response::HTTP_SEE_OTHER);
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

            return $this->render('modules/pregrado/comision_nacional/new.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolComision' => $rolComisionRepository->findBy(['activo' => true], ['nombre' => 'asc'])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_nacional_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona", name="app_comision_nacional_asociar_persona_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function asociarPersona(Request $request)
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
     * @Route("/{id}/eliminar_persona_asociada", name="app_comision_nacional_eliminar_persona_asignada_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param Persona $persona
     * @return Response
     */
    public function eliminarPersonaAsociada(Request $request, Persona $persona)
    {
        try {
            $nuevoArray = [];
            foreach ($request->getSession()->get('array_personas_asignadas') as $value) {
                if ($value['id_persona'] != $persona->getId()) {
                    $nuevoArray[] = $value;
                }
            }
            $request->getSession()->set('array_personas_asignadas', $nuevoArray);
            return $this->redirectToRoute('app_comision_nacional_registrar', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/modificar", name="app_comision_nacional_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @param Comision $comision
     * @param ComisionNacionalRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function modificar(Request $request, MiembrosComisionNacionalRepository $miembrosComisionRepository, ComisionNacional $comision, ComisionNacionalRepository $comisionRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(ComisionNacionalType::class, $comision, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comisionRepository->edit($comision);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_nacional_index', [], Response::HTTP_SEE_OTHER);
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


            return $this->render('modules/pregrado/comision_nacional/edit.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolComision' => $rolComisionRepository->findBy(['activo' => true], ['nombre' => 'asc']),
                'comision' => $comision
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_nacional_modificar', ['id' => $comision->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona_modificar", name="app_comision_nacional_asociar_persona_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param ComisionNacionalRepository $comisionRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function asociarPersonaModificar(Request $request, PersonaRepository $personaRepository, RolComisionRepository $rolComisionRepository, ComisionNacionalRepository $comisionRepository, EntityManagerInterface $em)
    {
        try {
            $arrayIds = $request->request->get('arrayId');
            if (is_array($arrayIds)) {
                foreach ($arrayIds as $value) {
                    $miembroComisionEntity = new MiembrosComisionNacional();
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
     * @Route("/{id}/eliminar_persona_asignada_modificar", name="app_comision_nacional_eliminar_persona_asignada_modificar", methods={"GET"})
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @return Response
     */
    public function eliminarPersonaAsignadaModificar(Request $request, Persona $persona, PersonaRepository $personaRepository, MiembrosComisionNacionalRepository $miembrosComisionRepository)
    {
        try {
            $comision = $request->getSession()->get('idComision');
            if ($personaRepository->find($persona->getId()) instanceof Persona) {
                $miembro = $miembrosComisionRepository->findOneBy(['miembro' => $persona->getId(), 'comision' => $comision]);
                $miembrosComisionRepository->remove($miembro, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_nacional_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_nacional_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_nacional_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_comision_nacional_detail", methods={"GET", "POST"})
     * @param ComisionNacional $comision
     * @param MiembrosComisionNacionalRepository $miembrosComisionRepository
     * @return Response
     */
    public function detail(ComisionNacional $comision, MiembrosComisionNacionalRepository $miembrosComisionRepository)
    {
        return $this->render('modules/pregrado/comision_nacional/detail.html.twig', [
            'item' => $comision,
            'asignadas' => $miembrosComisionRepository->findBy(['comision' => $comision->getId()]),
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_comision_nacional_eliminar", methods={"GET"})
     * @param Request $request
     * @param Comision $comision
     * @param ComisionNacionalRepository $comisionRepository
     * @return Response
     */
    public function eliminar(Request $request, ComisionNacional $comision, ComisionNacionalRepository $comisionRepository)
    {
        try {
            if ($comisionRepository->find($comision) instanceof ComisionNacional) {
                $comisionRepository->remove($comision, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_nacional_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_nacional_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_nacional_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
