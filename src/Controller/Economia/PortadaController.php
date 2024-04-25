<?php

namespace App\Controller\Economia;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/economia/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_INSTIT")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_economia_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/economia/portada/index.html.twig');

    }


}
