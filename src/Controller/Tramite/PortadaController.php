<?php

namespace App\Controller\Tramite;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_TRAMITES", "ROLE_HOME_ECONOMIA")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_tramite_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/tramite/portada/index.html.twig');

    }


}
