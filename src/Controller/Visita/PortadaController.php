<?php

namespace App\Controller\Visita;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/visita/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_INSTIT")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_visita_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/visita/portada/index.html.twig');

    }


}
