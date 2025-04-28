<?php

namespace App\Controller\RRHH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/rrhh/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_INFORMATIZACION")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_rrhh_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/rrhh/portada/index.html.twig');

    }


}
