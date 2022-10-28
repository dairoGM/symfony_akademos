<?php

namespace App\Controller\Postgrado;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\Comision;
use App\Entity\Postgrado\MiembrosComision;
use App\Form\Postgrado\ComisionType;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\ComisionRepository;
use App\Repository\Postgrado\MiembrosComisionRepository;
use App\Repository\Postgrado\RolComisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/postgrado/comision")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class ComisionController extends AbstractController
{

    /**
     * @Route("/", name="app_comision_index", methods={"GET"})
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function index(Request $request, ComisionRepository $comisionRepository)
    {
        $request->getSession()->remove('array_personas_asignadas');
        return $this->render('modules/postgrado/comision/index.html.twig', [
            'registros' => $comisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_comision_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ComisionRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function registrar(Request $request, EntityManagerInterface $em, ComisionRepository $comisionRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $comisionEntity = new Comision();
            $form = $this->createForm(ComisionType::class, $comisionEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
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


                $request->getSession()->remove('array_personas_asignadas');
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
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

            return $this->render('modules/postgrado/comision/new.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolComision' => $rolComisionRepository->findBy(['activo' => true], ['nombre' => 'asc'])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona", name="app_comision_asociar_persona_registrar", methods={"GET", "POST"})
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
     * @Route("/{id}/eliminar_persona_asociada", name="app_comision_eliminar_persona_asignada_registrar", methods={"GET", "POST"})
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
            return $this->redirectToRoute('app_comision_registrar', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/modificar", name="app_comision_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @param Comision $comision
     * @param ComisionRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function modificar(Request $request, MiembrosComisionRepository $miembrosComisionRepository, Comision $comision, ComisionRepository $comisionRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(ComisionType::class, $comision, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comisionRepository->edit($comision);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
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


            return $this->render('modules/postgrado/comision/edit.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolComision' => $rolComisionRepository->findBy(['activo' => true], ['nombre' => 'asc']),
                'comision' => $comision
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_modificar', ['id' => $comision->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona_modificar", name="app_comision_asociar_persona_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param ComisionRepository $comisionRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function asociarPersonaModificar(Request $request, PersonaRepository $personaRepository, RolComisionRepository $rolComisionRepository, ComisionRepository $comisionRepository, EntityManagerInterface $em)
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
     * @Route("/{id}/eliminar_persona_asignada_modificar", name="app_comision_eliminar_persona_asignada_modificar", methods={"GET"})
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @return Response
     */
    public function eliminarPersonaAsignadaModificar(Request $request, Persona $persona, PersonaRepository $personaRepository, MiembrosComisionRepository $miembrosComisionRepository)
    {
        try {
            $comision = $request->getSession()->get('idComision');
            if ($personaRepository->find($persona->getId()) instanceof Persona) {
                $miembro = $miembrosComisionRepository->findOneBy(['miembro' => $persona->getId(), 'comision' => $comision]);
                $miembrosComisionRepository->remove($miembro, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_comision_detail", methods={"GET", "POST"})
     * @param Comision $comision
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @return Response
     */
    public function detail(Comision $comision, MiembrosComisionRepository $miembrosComisionRepository)
    {
        return $this->render('modules/postgrado/comision/detail.html.twig', [
            'item' => $comision,
            'asignadas' => $miembrosComisionRepository->findBy(['comision' => $comision->getId()]),
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_comision_eliminar", methods={"GET"})
     * @param Request $request
     * @param Comision $comision
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function eliminar(Request $request, Comision $comision, ComisionRepository $comisionRepository)
    {
        try {
            if ($comisionRepository->find($comision) instanceof Comision) {
                $comisionRepository->remove($comision, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
