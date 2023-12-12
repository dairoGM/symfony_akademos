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

        $parametros['carreras_aprobadas'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoByTipo([2], 1));
        $parametros['cursos_aprobados'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoByTipo([2], 2));
        $parametros['total_aprobado'] = $parametros['carreras_aprobadas']+$parametros['cursos_aprobados'];



        $parametros['programas_rechazados'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([3]));
        $parametros['programas_reabiertos'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([7]));
        $parametros['programas_extintos'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([8]));
        $parametros['programas_desactivados'] = count($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobado([6]));

        $parametros['carrera_por_rama_ciencia'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorRamaCiencia(1);
        $parametros['curso_por_rama_ciencia'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorRamaCiencia(2);

        $parametros['carrera_por_modalidad'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorModalidad(1);
        $parametros['curso_por_modalidad'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorModalidad(2);

        $parametros['carrera_por_categoria_acreditacion'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorCategoriaAcreditacion(1);
        $parametros['curso_por_categoria_acreditacion'] = $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorCategoriaAcreditacion(2);

        $parametros['carrera_por_centro_rector'] = json_encode($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorCentroRector(1));
        $parametros['tecnico_por_centro_rector'] = json_encode($solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoPorCentroRector(2));
        return $this->render('modules/pregrado/portada/index.html.twig', $parametros);

    }


}
