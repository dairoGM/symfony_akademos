<?php

namespace App\Controller\Traza;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/traza/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_TRAZAS")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_traza_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/traza/portada/index.html.twig');
    }


}
