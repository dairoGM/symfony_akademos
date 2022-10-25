<?php

namespace App\Controller\Estructura;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_STRUCT")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_estructura_portada", methods={"GET"})
     * @param AreaResultadoClaveRepository $areaResultadoRepository
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/estructura/portada/index.html.twig');

    }


}
