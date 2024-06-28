<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\AplazamientoSolicitud;
use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Evaluacion\Solicitud;
use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Evaluacion\AprobarSolicitudType;
use App\Form\Evaluacion\RechazarSolicitudType;
use App\Form\Evaluacion\SolicitudType;
use App\Repository\Evaluacion\AplazamientoSolicitudRepository;
use App\Repository\Evaluacion\ComisionRepository;
use App\Repository\Evaluacion\EstadoAplazamientoRepository;
use App\Repository\Evaluacion\EstadoSolicitudRepository;
use App\Repository\Evaluacion\SolicitudRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Repository\Security\UserRepository;
use App\Repository\Tramite\InstitucionExtranjeraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/informe_autoevaluacion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_INFORME_AUTOEVALUACION")
 */
class InformeAutoevaluacionController extends AbstractController
{

    /**
     * @Route("/", name="app_informe_autoevaluacion_index", methods={"GET"})
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function index(SolicitudRepository $solicitudRepository)
    {
        $registros = $solicitudRepository->getInformeAutoevaluacion();
        $respose = [];
        if (is_array($registros)) {
            foreach ($registros as $value) {
                $class = null;
                if ($value->getEstadoInformeAutoevaluacion() == 'Aprobado') {
                    $class = 'ms-status bg-green';
                }
                if ($value->getEstadoInformeAutoevaluacion() == 'Rechazado') {
                    $class = 'ms-status bg-red';
                }
                $value->classParams = $class;
                $respose[] = $value;
            }
        }
        return $this->render('modules/evaluacion/informe_autoevaluacion/index.html.twig', [
            'registros' => $respose
        ]);
    }

    /**
     * @Route("/{id}/aprobar", name="app_informe_autoevaluacion_aprobar", methods={"GET"})
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function aprobar(Solicitud $solicitud, SolicitudRepository $solicitudRepository)
    {
        try {
            $solicitud->setEstadoInformeAutoevaluacion('Aprobado');
            $solicitudRepository->edit($solicitud, true);
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_informe_autoevaluacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_informe_autoevaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/rechazar", name="app_informe_autoevaluacion_rechazar", methods={"GET"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function rechazar(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository)
    {
        try {
            $solicitud->setEstadoInformeAutoevaluacion('Rechazado');
            $solicitudRepository->edit($solicitud, true);
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_informe_autoevaluacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_informe_autoevaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_informe_autoevaluacion_eliminar", methods={"GET"})
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function eliminar(Solicitud $solicitud, SolicitudRepository $solicitudRepository)
    {
        try {
            $solicitud->setEstadoInformeAutoevaluacion(null);
            $solicitud->setInformeAutoevaluacion(null);
            $solicitudRepository->edit($solicitud, true);
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_informe_autoevaluacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_informe_autoevaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
