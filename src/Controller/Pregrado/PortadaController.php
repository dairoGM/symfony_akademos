<?php

namespace App\Controller\Pregrado;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_PERSONAL")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_pregrado_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/pregrado/portada/index.html.twig');

    }


}
