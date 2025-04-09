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
 * @Route("/pregrado/reporte/programa_academico_por_universidad")
 * @IsGranted("ROLE_ADMIN", "ROLE_PREGRADO_REPORTE_PROG_POR_UNIVERSIDAD")
 */
class ProgramaAcademicoPorUniversidadController extends AbstractController
{

    /**
     * @Route("/", name="app_reporte_programa_academico_por_universidad_index", methods={"GET", "POST"})
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return Response
     */
    public function index(Request $request, InstitucionRepository $institucionRepository, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository)
    {
        $idUniversidad = $request->getSession()->has('pregrado_programa_por_universidad') ? $request->getSession()->get('pregrado_programa_por_universidad') : [];

        $filter = null;
        if (is_array($idUniversidad) && count($idUniversidad) > 0) {
            $filter = implode(',', $idUniversidad);
        }

        $response = $solicitudProgramaAcademicoInstitucionRepository->getProgramasV3($filter);
        return $this->render('modules/pregrado/reportes/programa_academico/por_universidad/index.html.twig', [
            'registros' => $response,
            'universidad' => $institucionRepository->findBy([], ['nombre' => 'ASC']),
            'id_universidad' => $idUniversidad
        ]);
    }


    /**
     * @Route("/filter", name="app_reporte_programa_academico_por_universidad_filter", methods={"POST"})
     * @return Response
     */
    public function filter(Request $request)
    {
        $allPost = $request->request->all();
        $idUniversidad = $allPost['id_universidad'] ?? null;
        $request->getSession()->set('pregrado_programa_por_universidad', $idUniversidad);
        return $this->json(['response' => 'OK']);
    }
}
