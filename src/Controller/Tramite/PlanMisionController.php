<?php

namespace App\Controller\Tramite;

use App\Entity\Tramite\PlanMision;
use App\Form\Tramite\PlanMisionType;
use App\Repository\Tramite\PlanMisionRepository;
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
    public function detail(PlanMision $planMision)
    {
        return $this->render('modules/tramite/plan_mision/detail.html.twig', [
            'item' => $planMision,
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

}
