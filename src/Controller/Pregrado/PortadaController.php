<?php

namespace App\Controller\Pregrado;

use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/portada")
 * @IsGranted("ROLE_ADMIN", "ROLE_HOME_PREGR")
 */
class PortadaController extends AbstractController
{

    /**
     * @Route("", name="app_pregrado_portada", methods={"GET"})
     * @return Response
     */
    public function index(SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        $parametros['total_solicitudes'] = count($solicitudProgramaAcademicoRepository->findAll());
        $parametros['programas_aprobados'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([2]));
        $parametros['programas_rechazados'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([3]));
        $parametros['programas_reabiertos'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([7]));
        $parametros['programas_extintos'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([8]));
        $parametros['programas_desactivados'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([6]));
        $parametros['por_rama_ciencia'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorRamaCiencia();
        $parametros['por_modalidad'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorModalidad();
        $parametros['por_categoria_acreditacion'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorCategoriaAcreditacion();
        $parametros['por_centro_rector'] = json_encode($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorCentroRector());
        return $this->render('modules/pregrado/portada/index.html.twig', $parametros);

    }


}
