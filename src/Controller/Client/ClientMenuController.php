<?php

namespace App\Controller\Client;

use App\Repository\Planificacion\ObjetivoGeneralRepository;
use App\Repository\Planificacion\PlanObjetivoGeneralRepository;
use App\Repository\Planificacion\PlanRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientMenuController extends AbstractController
{

    public function index(): Response
    {

        return $this->render('cliente/menu.html.twig', [
            'objetivosGenerales' => [],
            'planes' => [],
            'planUser' => []
        ]);
    }

    /**
     * @Route("/change/filter/plan/user/{id}", name="app_change_filter_plan_user")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function appChangeFilterPlanUser(Request $request, $id)
    {
        $request->getSession()->set('user_plan_filter', $id);
        return $this->json(['status' => 200]);
    }


}
