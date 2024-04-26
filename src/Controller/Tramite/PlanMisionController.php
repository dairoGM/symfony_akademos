<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\MiembrosComision;
use App\Entity\Tramite\PlanMision;
use App\Entity\Tramite\PlanMisionDetalles;
use App\Form\Tramite\PlanMisionType;
use App\Repository\Estructura\PaisRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Tramite\PlanMisionDetallesRepository;
use App\Repository\Tramite\PlanMisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/plan_mision")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATACRED")
 */
class PlanMisionController extends AbstractController
{

    /**
     * @Route("/", name="app_plan_mision_index", methods={"GET"})
     * @param planMisionRepository $planMisionRepository
     * @return Response
     */
    public function index(PlanMisionRepository $planMisionRepository)
    {
        return $this->render('modules/tramite/plan_mision/index.html.twig', [
            'registros' => $planMisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_plan_mision_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param planMisionRepository $planMisionRepository
     * @return Response
     */
    public function registrar(Request $request, PlanMisionRepository $planMisionRepository)
    {
        try {
            $entidad = new PlanMision();
            $form = $this->createForm(PlanMisionType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $planMisionRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_mision_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/plan_mision/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_mision_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_plan_mision_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PlanMision $planMision
     * @param planMisionRepository $planMisionRepository
     * @return Response
     */
    public function modificar(Request $request, PlanMision $planMision, PlanMisionRepository $planMisionRepository)
    {
        try {
            $form = $this->createForm(PlanMisionType::class, $planMision, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $planMisionRepository->edit($planMision);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_mision_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/plan_mision/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_mision_modificar', ['id' => $planMision], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_plan_mision_detail", methods={"GET", "POST"})
     * @param PlanMision $planMision
     * @return Response
     */
    public function detail(PlanMision $planMision, PlanMisionDetallesRepository $planMisionDetallesRepository)
    {
        return $this->render('modules/tramite/plan_mision/detail.html.twig', [
            'item' => $planMision,
            'registrosAsignados' => $planMisionDetallesRepository->findBy(['planMision' => $planMision->getId()]),

        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_plan_mision_eliminar", methods={"GET"})
     * @param PlanMision $planMision
     * @param planMisionRepository $planMisionRepository
     * @return Response
     */
    public function eliminar(PlanMision $planMision, PlanMisionRepository $planMisionRepository)
    {
        try {
            if ($planMisionRepository->find($planMision) instanceof PlanMision) {
                $planMisionRepository->remove($planMision, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_mision_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_plan_mision_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_mision_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/configurar", name="app_plan_mision_configurar", methods={"GET", "POST"})
     * @param Request $request
     * @param PlanMision $planMision
     * @return Response
     */
    public function configurar(Request $request, PaisRepository $paisRepository, PersonaRepository $personaRepository, PlanMision $planMision, PlanMisionDetallesRepository $planMisionDetallesRepository)
    {
        try {
            $personas = $personaRepository->findBy([], ['primerNombre' => 'asc']);
            $registrosAsignados = $planMisionDetallesRepository->findBy(['planMision' => $planMision->getId()]);
            $registros = [];
            foreach ($personas as $value) {
                $exist = false;
                if (is_array($registrosAsignados)) {
                    foreach ($registrosAsignados as $value1) {
                        if ($value->getId() == $value1->getPersona()->getId()) {
                            $exist = true;
                            break;
                        }
                    }
                }

                if (!$exist) {
                    $registros[] = $value;
                }
            }

            return $this->render('modules/tramite/plan_mision/configurar.html.twig', [
                'registros' => $registros,
                'registrosAsignados' => $registrosAsignados,
                'paises' => $paisRepository->findBy([], ['nombre' => 'asc']),
                'planMisionId' => $planMision->getId(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_mision_configurar', ['id' => $planMision->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/asociar_persona", name="app_plan_mision_persona_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function asociarPersona(Request $request, EntityManagerInterface $em, PersonaRepository $personaRepository, PaisRepository $paisRepository, PlanMisionRepository $planMisionRepository)
    {
        try {
            $arrayIds = $request->request->get('arrayId');

            if (is_array($arrayIds)) {

                foreach ($arrayIds as $value) {
                    $planMisionDetalles = new PlanMisionDetalles();
                    $planMisionDetalles->setPersona($personaRepository->find($value['id_persona']));
                    $planMisionDetalles->setPais($paisRepository->find($value['id_pais']));
                    $planMisionDetalles->setDuracion($value['duracion']);
                    $planMisionDetalles->setObjetivo($value['objetivo']);
                    $temp = explode('/', $value['fecha']);
                    $planMisionDetalles->setFecha(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));
                    $planMisionDetalles->setPlanMision($planMisionRepository->find($value['plan_mision_id']));
                    $em->persist($planMisionDetalles);
                }
                $em->flush();

            }
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }


    /**
     * @Route("/{id}/eliminar_persona_asociada", name="app_plan_mision_eliminar_persona_asignada_registrar", methods={"GET", "POST"})
     * @param Persona $planMisionDetalles
     * @return Response
     */
    public function eliminarPersonaAsociada(EntityManagerInterface $em, PlanMisionDetalles $planMisionDetalles)
    {
        try {
            $em->remove($planMisionDetalles);
            $em->flush();
            return $this->redirectToRoute('app_plan_mision_configurar', ['id' => $planMisionDetalles->getPlanMision()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }
}
