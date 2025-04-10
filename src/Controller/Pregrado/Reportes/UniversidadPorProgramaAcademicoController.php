<?php

namespace App\Controller\Pregrado\Reportes;

use App\Entity\NotificacionesUsuario;
use App\Entity\Pregrado\MiembrosComisionNacional;
use App\Entity\Pregrado\ProgramaAcademicoDesactivado;
use App\Entity\Pregrado\ProgramaAcademicoReabierto;
use App\Entity\Pregrado\ProgramaAcademicoReabiertoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use App\Export\Personal\ExportListPersonaToPdf;
use App\Export\Pregrado\ExportListSolicitudProgramaAcademicoToPdf;
use App\Export\Pregrado\ExportListSolicitudUniversidadesAsignadasToPdf;
use App\Form\Pregrado\AprobarModificarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\ProgramaAcademicoDesactivadoType;
use App\Form\Pregrado\ProgramaAcademicoReabiertoType;
use App\Form\Pregrado\SolicitudProgramaComisionType;
use App\Form\Pregrado\SolicitudProgramaInstitucionType;
use App\Form\Pregrado\SolicitudProgramaPlanEstudioType;
use App\Repository\Institucion\CategoriaAcreditacionRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
use App\Repository\Pregrado\MiembrosComisionNacionalRepository;
use App\Repository\Pregrado\ModificacionPlanEstudioRepository;
use App\Repository\Pregrado\ProgramaAcademicoDesactivadoRepository;
use App\Repository\Pregrado\ProgramaAcademicoReabiertoInstitucionRepository;
use App\Repository\Pregrado\ProgramaAcademicoReabiertoRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoComisionNacionalRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoPlanEstudioRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Services\DoctrineHelper;
use App\Services\HandlerFop;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/reporte/universidad_por_programa_academico")
 * @IsGranted("ROLE_ADMIN", "ROLE_PREGRADO_REPORTE_UNIVERSIDAD_POR_PROG")
 */
class UniversidadPorProgramaAcademicoController extends AbstractController
{

    /**
     * @Route("/", name="app_reporte_universidad_por_programa_academico_index", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @param SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository
     * @return Response
     */
    public function index(Request $request, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository)
    {
        $idPrograma = $request->getSession()->has('universidad_por_pregrado_programa') ? $request->getSession()->get('universidad_por_pregrado_programa') : [];

        $filter = null;
        if (is_array($idPrograma) && count($idPrograma) > 0) {
            $filter = implode(',', $idPrograma);
        }

        $response = $solicitudProgramaAcademicoInstitucionRepository->getInstitucionesV3($filter);
        return $this->render('modules/pregrado/reportes/universidad/por_programa_academico/index.html.twig', [
            'registros' => $response,
            'programaAcademico' => $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademicoAprobadoFiltro([2, 4, 6, 7]),
            'id_programa' => $idPrograma
        ]);
    }


    /**
     * @Route("/filter", name="app_reporte_universidad_por_programa_academico_filter", methods={"POST"})
     * @return Response
     */
    public function filter(Request $request)
    {
        $allPost = $request->request->all();
        $idPrograma = $allPost['id_programa'] ?? null;
        $request->getSession()->set('universidad_por_pregrado_programa', $idPrograma);
        return $this->json(['response' => 'OK']);
    }
}
