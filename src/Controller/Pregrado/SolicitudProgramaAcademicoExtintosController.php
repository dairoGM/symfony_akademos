<?php

namespace App\Controller\Pregrado;

use App\Entity\NotificacionesUsuario;
use App\Entity\Pregrado\ProgramaAcademicoDesactivado;
use App\Entity\Pregrado\ProgramaAcademicoReabierto;
use App\Entity\Pregrado\ProgramaAcademicoReabiertoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\ProgramaAcademicoDesactivadoType;
use App\Form\Pregrado\ProgramaAcademicoReabiertoType;
use App\Form\Pregrado\SolicitudProgramaComisionType;
use App\Form\Pregrado\SolicitudProgramaInstitucionType;
use App\Form\Pregrado\SolicitudProgramaPlanEstudioType;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\NotificacionesUsuarioRepository;
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
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/solicitud_programa_academico_extinto")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class SolicitudProgramaAcademicoExtintosController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_academico_extinto_index", methods={"GET"})
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, ProgramaAcademicoDesactivadoRepository $programaAcademicoDesactivadoRepository)
    {
        $items = [];
        /*Cuando se ejecute el cron, listar solo las solicitudes en estado 9*/
        $registros = $solicitudProgramaRepository->getSolicitudProgramaAcademicoAprobado([2, 4, 5, 7, 8]);
        if (is_array($registros)) {
            date_default_timezone_set("America/New_York");
            foreach ($registros as $value) {
                $value->noTieneFechaEliminacion = true;
                $mostrar = true;
                $var = $programaAcademicoDesactivadoRepository->findBy(['solicitudProgramaAcademico' => $value->getId()]);
                if (is_array($var) && count($var) > 0) {
                    if (!empty($var[0]->getFechaEliminacion())) {
                        $fechaEliminacion = $var[0]->getFechaEliminacion();
                        $fechaActual = new \DateTime('now');
                        if ($fechaActual > $fechaEliminacion) {
                            $mostrar = false;
                        }
                    }
                    $value->noTieneFechaEliminacion = false;
                }
                if (!$mostrar) {
                    $items[] = $value;
                }
            }
        }
        return $this->render('modules/pregrado/solicitud_programa_academico_extinto/index.html.twig', [
            'registros' => $items,
        ]);
    }

    /**
     * @Route("/{id}/activar", name="app_solicitud_programa_academico_extinto_activar", methods={"GET"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function activar(Request $request, ProgramaAcademicoDesactivadoRepository $programaAcademicoDesactivadoRepository, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
    {
        try {
            if ($solicitudProgramaRepository->find($solicitudPrograma) instanceof SolicitudProgramaAcademico) {
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(2));
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                $progDesactivado = $programaAcademicoDesactivadoRepository->findBy(['solicitudProgramaAcademico' => $solicitudPrograma->getId()]);
                if (is_array($progDesactivado)) {
                    foreach ($progDesactivado as $value) {
                        $programaAcademicoDesactivadoRepository->remove($value, true);
                    }
                }

                $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_extinto_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_academico_extinto_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_extinto_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     *
     * /**
     * @Route("/{id}/detail", name="app_solicitud_programa_academico_extinto_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @return Response
     */
    public function detail(Request $request, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository, ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        $planEstudio = $solicitudProgramaAcademicoPlanEstudioRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]);
        $planEstudioAsociado = -1;
        if (is_array($planEstudio) && count($planEstudio) > 0) {
            $planEstudioAsociado = $planEstudio[0]->getPlanEstudio()->getId();
        }
        return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/detail.html.twig', [
            'item' => $solicitudProgramaAcademico,
            'format' => 'col2',
            'universidades' => $solicitudProgramaAcademicoInstitucionRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]),
            'modificacionesPlanEstudio' => $modificacionPlanEstudioRepository->findBy(['planEstudio' => $planEstudioAsociado])
        ]);
    }

}
