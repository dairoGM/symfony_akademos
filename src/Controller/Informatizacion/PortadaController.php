<?php

namespace App\Controller\Informatizacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_INFORMATIZACION")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_informatizacion_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/informatizacion/portada/index.html.twig');

    }


}
