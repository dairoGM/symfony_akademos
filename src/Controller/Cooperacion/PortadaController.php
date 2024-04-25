<?php

namespace App\Controller\Cooperacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/cooperacion/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_INSTIT")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_cooperacion_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/cooperacion/portada/index.html.twig');

    }


}
