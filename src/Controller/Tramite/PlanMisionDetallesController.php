<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\MiembrosComision;
use App\Entity\Tramite\PlanMision;
use App\Entity\Tramite\PlanMisionDetalles;
use App\Form\Tramite\PlanMisionType;
use App\Repository\Estructura\PaisRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Security\UserRepository;
use App\Repository\Tramite\PlanMisionDetallesRepository;
use App\Repository\Tramite\PlanMisionRepository;
use App\Services\HandlerFop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/plan_mision_detalles")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PLAN_MISION_DETALLES")
 */
class PlanMisionDetallesController extends AbstractController
{

    /**
     * @Route("/", name="app_plan_mision_detalles_index", methods={"GET"})
     * @param planMisionRepository $planMisionRepository
     * @return Response
     */
    public function index(Request $request, PlanMisionRepository $planMisionRepository, PlanMisionDetallesRepository $planMisionDetallesRepository)
    {
        $planesMision = $planMisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        $last = $planesMision[count($planesMision) - 1]->getId();
        if (!$request->getSession()->has('filter_plan_mision')) {
            $request->getSession()->set('filter_plan_mision', $last);
        }

        return $this->render('modules/tramite/plan_mision_detalles/index.html.twig', [
            'registros' => $planesMision,
            'last' => $request->getSession()->get('filter_plan_mision'),
            'registrosAsignados' => $planMisionDetallesRepository->findBy(['planMision' => $request->getSession()->get('filter_plan_mision')]),
        ]);
    }


    /**
     * @Route("/cambio/filtro/plan_plan/{id}", name="app_cambio_filtro_plan")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cambiarFiltroPlan(Request $request, $id)
    {
        $request->getSession()->set('filter_plan_mision', $id);
        return $this->json(['status' => 200]);
    }

}
