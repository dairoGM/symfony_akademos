<?php

namespace App\Controller\Reporte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/reporte/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_REPORT")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_reporte_portada", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {


        return $this->render('modules/reporte/portada/index.html.twig');

    }


}
