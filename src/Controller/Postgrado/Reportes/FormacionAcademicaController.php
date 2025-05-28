<?php

namespace App\Controller\Postgrado\Reportes;

use App\Form\Postgrado\CambioEstadoProgramaType;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/postgrado/reporte/formacion_academica")
 * @IsGranted("ROLE_ADMIN", "ROLE_POSGRADO_REPORTE_FORMACION_ACADEMICA")
 */
class FormacionAcademicaController extends AbstractController
{

    /**
     * @Route("/", name="app_reporte_postgrado_formacion_academica_index", methods={"GET"})
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function indexFormacionAcademica(SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/postgrado/reportes/formacion_academica/index.html.twig', [
            'registros' => $solicitudProgramaRepository->findBy(['tipoPrograma' => [4, 5], 'estadoPrograma' => 7], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

}
