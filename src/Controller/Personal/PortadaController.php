<?php

namespace App\Controller\Personal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_PERSONAL")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_personal_portada", methods={"GET"})
     * @param AreaResultadoClaveRepository $areaResultadoRepository
     * @return Response
     */
    public function index()
    {
        return $this->render('modules/personal/portada/index.html.twig');

    }


}
