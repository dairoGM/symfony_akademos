<?php

namespace App\Controller\Postgrado;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\Copep;
use App\Entity\Postgrado\MiembrosCopep;
use App\Form\Postgrado\CopepType;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\CopepRepository;
use App\Repository\Postgrado\MiembrosCopepRepository;
use App\Repository\Postgrado\RolComisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/postgrado/copep")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class CopepController extends AbstractController
{

    /**
     * @Route("/", name="app_copep_index", methods={"GET"})
     * @param CopepRepository $copepRepository
     * @return Response
     */
    public function index(Request $request, CopepRepository $copepRepository)
    {
        $request->getSession()->remove('array_personas_asignadas');
        return $this->render('modules/postgrado/copep/index.html.twig', [
            'registros' => $copepRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_copep_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CopepRepository $copepRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function registrar(Request $request, EntityManagerInterface $em, CopepRepository $copepRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $copepEntity = new Copep();
            $form = $this->createForm(CopepType::class, $copepEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $all = $copepRepository->findAll();
                foreach ($all as $value) {
                    $value->setActivo(false);
                    $copepRepository->edit($value, true);
                }


                $copepRepository->add($copepEntity, true);

                if ($request->getSession()->has('array_personas_asignadas')) {
                    foreach ($request->getSession()->get('array_personas_asignadas') as $value) {
                        $miembroCopepEntity = new MiembrosCopep();
                        $miembroCopepEntity->setMiembro($personaRepository->find($value['id_persona']));
                        $miembroCopepEntity->setRolComision($rolComisionRepository->find($value['id_rol']));
                        $miembroCopepEntity->setCopep($copepEntity);
                        $em->persist($miembroCopepEntity);
                    }
                    $em->flush();
                }


                $request->getSession()->remove('array_personas_asignadas');
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_copep_index', [], Response::HTTP_SEE_OTHER);
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

            return $this->render('modules/postgrado/copep/new.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolCopep' => $rolComisionRepository->findBy(['activo' => true, 'copep' => true], ['nombre' => 'asc'])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_copep_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona", name="app_copep_asociar_persona_registrar", methods={"GET", "POST"})
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
     * @Route("/{id}/eliminar_persona_asociada", name="app_copep_eliminar_persona_asignada_registrar", methods={"GET", "POST"})
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
            return $this->redirectToRoute('app_copep_registrar', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/modificar", name="app_copep_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param MiembrosCopepRepository $miembrosCopepRepository
     * @param Copep $copep
     * @param CopepRepository $copepRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function modificar(Request $request, MiembrosCopepRepository $miembrosCopepRepository, Copep $copep, CopepRepository $copepRepository, RolComisionRepository $rolComisionRepository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(CopepType::class, $copep, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $copepRepository->edit($copep);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_copep_index', [], Response::HTTP_SEE_OTHER);
            }
            $request->getSession()->set('idCopep', $copep->getId());
            $arrayIdAsignados = [];
            $asignadas = [];
            $temp = $miembrosCopepRepository->findBy(['copep' => $copep->getId()]);
            foreach ($temp as $value) {
                $arrayIdAsignados[] = $value->getMiembro()->getId();
                $item = $value->getMiembro();
                $item->rolComision = $value->getRolComision()->getNombre();
                $asignadas[] = $item;
            }


            return $this->render('modules/postgrado/copep/edit.html.twig', [
                'form' => $form->createView(),
                'personas' => $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados),
                'asignadas' => $asignadas,
                'rolCopep' => $rolComisionRepository->findBy(['activo' => true, 'copep' => true], ['nombre' => 'asc']),
                'copep' => $copep
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_copep_modificar', ['id' => $copep->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_persona_modificar", name="app_copep_asociar_persona_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param CopepRepository $copepRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function asociarPersonaModificar(Request $request, PersonaRepository $personaRepository, RolComisionRepository $rolComisionRepository, CopepRepository $copepRepository, EntityManagerInterface $em)
    {
        try {
            $arrayIds = $request->request->get('arrayId');
            if (is_array($arrayIds)) {
                foreach ($arrayIds as $value) {
                    $miembroCopepEntity = new MiembrosCopep();
                    $miembroCopepEntity->setMiembro($personaRepository->find($value['id_persona']));
                    $miembroCopepEntity->setRolComision($rolComisionRepository->find($value['id_rol']));
                    $miembroCopepEntity->setCopep($copepRepository->find($request->getSession()->get('idCopep')));
                    $em->persist($miembroCopepEntity);
                }
                $em->flush();
            }
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/eliminar_persona_asignada_modificar", name="app_copep_eliminar_persona_asignada_modificar", methods={"GET"})
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param MiembrosCopepRepository $miembrosCopepRepository
     * @return Response
     */
    public function eliminarPersonaAsignadaModificar(Request $request, Persona $persona, PersonaRepository $personaRepository, MiembrosCopepRepository $miembrosCopepRepository)
    {
        try {
            $copep = $request->getSession()->get('idCopep');
            if ($personaRepository->find($persona->getId()) instanceof Persona) {
                $miembro = $miembrosCopepRepository->findOneBy(['miembro' => $persona->getId(), 'copep' => $copep]);
                $miembrosCopepRepository->remove($miembro, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_copep_modificar', ['id' => $copep], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_copep_modificar', ['id' => $copep], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_copep_modificar', ['id' => $copep], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_copep_detail", methods={"GET", "POST"})
     * @param Copep $copep
     * @param MiembrosCopepRepository $miembrosCopepRepository
     * @return Response
     */
    public function detail(Copep $copep, MiembrosCopepRepository $miembrosCopepRepository)
    {
        return $this->render('modules/postgrado/copep/detail.html.twig', [
            'item' => $copep,
            'asignadas' => $miembrosCopepRepository->findBy(['copep' => $copep->getId()]),
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_copep_eliminar", methods={"GET"})
     * @param Request $request
     * @param Copep $copep
     * @param CopepRepository $copepRepository
     * @return Response
     */
    public function eliminar(Request $request, Copep $copep, CopepRepository $copepRepository)
    {
        try {
            if ($copepRepository->find($copep) instanceof Copep) {
                $copepRepository->remove($copep, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_copep_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_copep_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_copep_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
