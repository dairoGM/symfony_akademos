<?php

namespace App\Controller\Evaluacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_EVALUACION")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_evaluacion_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/evaluacion/portada/index.html.twig');

    }


}
