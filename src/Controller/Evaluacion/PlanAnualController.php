<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Evaluacion\Solicitud;
use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Evaluacion\AprobarSolicitudType;
use App\Form\Evaluacion\RechazarSolicitudType;
use App\Form\Evaluacion\SolicitudType;
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
 * @Route("/evaluacion/plan_anual_evaluacion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PLAN_ANUAL_EVALUACION")
 */
class PlanAnualController extends AbstractController
{

    /**
     * @Route("/", name="app_plan_anual_evaluacion_index", methods={"GET"})
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function index(SolicitudRepository $solicitudRepository)
    {
        return $this->render('modules/evaluacion/plan_anual/index.html.twig', [
            'registros' => $solicitudRepository->findBy(['estadoSolicitud'=>3], ['id' => 'desc']),
        ]);
    }


    /**
     * @Route("/{id}/aprobar", name="app_plan_anual_evaluacion_aprobar", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function aprobar(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $form = $this->createForm(AprobarSolicitudType::class, $solicitud);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['aprobar_solicitud']['fechaAprobada']);
                $solicitud->setFechaAprobada(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));
                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find(3));

                $solicitudRepository->edit($solicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/solicitud/aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
