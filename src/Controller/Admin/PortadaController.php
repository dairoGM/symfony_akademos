<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/administracion/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_ADMIN")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_administracion_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/admin/portada/index.html.twig');

    }
}
