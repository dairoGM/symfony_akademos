<?php

namespace App\Controller\Convenio;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/convenio/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_CONVENIO")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_convenio_portada", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/convenio/portada/index.html.twig');

    }


}
