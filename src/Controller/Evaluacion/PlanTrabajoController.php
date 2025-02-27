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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/plan_trabajo")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PLAN_TRABAJO")
 */
class PlanTrabajoController extends AbstractController
{

    /**
     * @Route("/", name="app_plan_trabajo_index", methods={"GET"})
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function index(SolicitudRepository $solicitudRepository)
    {
        $registros = $solicitudRepository->getPlanTrabajo();
        $respose = [];
        if (is_array($registros)) {
            foreach ($registros as $value) {
                $class = null;
                if ($value->getEstadoPlanTrabajo() == 'Aprobado') {
                    $class = 'ms-status bg-green';
                }
                if ($value->getEstadoPlanTrabajo() == 'Rechazado') {
                    $class = 'ms-status bg-red';
                }
                $value->classParams = $class;
                $respose[] = $value;
            }
        }
        return $this->render('modules/evaluacion/plan_trabajo/index.html.twig', [
            'registros' => $respose
        ]);
    }

    /**
     * @Route("/{id}/aprobar", name="app_plan_trabajo_aprobar", methods={"GET"})
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function aprobar(Solicitud $solicitud, EntityManagerInterface $entityManager, EstadoSolicitudRepository $estadoSolicitudRepository, SolicitudRepository $solicitudRepository)
    {
        try {
            $solicitud->setEstadoPlanTrabajo('Aprobado');
            $solicitudRepository->edit($solicitud, true);
            $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_plan_trabajo_aprobado')));
            $entityManager->flush();
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_plan_trabajo_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_trabajo_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/rechazar", name="app_plan_trabajo_rechazar", methods={"POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function rechazar(Request $request, EntityManagerInterface $entityManager, Solicitud $solicitud, SolicitudRepository $solicitudRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $file = $request->files->get('additionalFile');
            $imagePath = $file->getClientOriginalName();

            if (file_exists('uploads/evaluacion/solicitud/plan_trabajo/motivos_rechazos/' . $imagePath)) {
                unlink('uploads/evaluacion/solicitud/plan_trabajo/motivos_rechazos/' . $imagePath);
            }
            $file->move("uploads/evaluacion/solicitud/plan_trabajo/motivos_rechazos", $imagePath);

            $solicitud->setEstadoPlanTrabajo('Rechazado');
            $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_plan_trabajo_rechazado')));
            $solicitud->setMotivoRechazo($imagePath);

            $solicitudRepository->edit($solicitud, true);
            $entityManager->flush();
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage());
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_plan_trabajo_eliminar", methods={"GET"})
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function eliminar(Solicitud $solicitud, SolicitudRepository $solicitudRepository)
    {
        try {
            $solicitud->setEstadoPlanTrabajo(null);
            $solicitud->setPlanTrabajo(null);
            $solicitudRepository->edit($solicitud, true);
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_plan_trabajo_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_trabajo_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
