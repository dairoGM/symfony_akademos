<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\AplazamientoSolicitud;
use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Evaluacion\Solicitud;
use App\Entity\Evaluacion\SolicitudDictamenComision;
use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Evaluacion\AplazarSolicitudType;
use App\Form\Evaluacion\AprobarSolicitudType;
use App\Form\Evaluacion\RechazarSolicitudType;
use App\Form\Evaluacion\SolicitudComisionType;
use App\Form\Evaluacion\SolicitudCTEType;
use App\Form\Evaluacion\SolicitudJANType;
use App\Form\Evaluacion\SolicitudType;
use App\Repository\Evaluacion\AplazamientoSolicitudRepository;
use App\Repository\Evaluacion\ComisionRepository;
use App\Repository\Evaluacion\EstadoAplazamientoRepository;
use App\Repository\Evaluacion\EstadoSolicitudRepository;
use App\Repository\Evaluacion\SolicitudDictamenComisionRepository;
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
    public function index(SolicitudRepository $solicitudRepository, ComisionRepository $comisionRepository, AplazamientoSolicitudRepository $aplazamientoSolicitudRepository)
    {
        $response = [];
        $registros = $solicitudRepository->findBy(['estadoSolicitud' => [3, 5, 6, 7, 8, 9]], ['id' => 'desc']);
        if (is_array($registros)) {
            foreach ($registros as $value) {
                $comisionAsignada = $comisionRepository->findBy(['solicitud' => $value->getId()]);
                $value->comision = isset($comisionAsignada[0]) ? $comisionAsignada[0]->getNombre() : null;

                $aplazamiento = $aplazamientoSolicitudRepository->findBy(['solicitud' => $value->getId()]);
                $value->aplazamiento = isset($aplazamiento[0]) ? $aplazamiento[0]->getEstadoAplazamiento()->getNombre() : null;
                $estadoAplazamiento = isset($aplazamiento[0]) ? $aplazamiento[0]->getEstadoAplazamiento()->getId() : null;
                $class = null;
                if ($estadoAplazamiento == 1) {
                    $class = 'bg-yellow';
                }
                if ($estadoAplazamiento == 2) {
                    $class = 'bg-green';
                }
                if ($estadoAplazamiento == 3) {
                    $class = 'bg-red';
                }
                $value->aplazamientoClass = $class;
                $value->estadoAplazamiento = isset($aplazamiento[0]) ? $aplazamiento[0]->getEstadoAplazamiento()->getNombre() : null;
                $response[] = $value;
            }
        }

        return $this->render('modules/evaluacion/plan_anual/index.html.twig', [
            'registros' => $response,
        ]);
    }


    /**
     * @Route("/{id}/aplazar", name="app_comision_evaluadora_aplazar", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function aplazar(Request $request, Solicitud $solicitud, AplazamientoSolicitudRepository $aplazamientoSolicitudRepository, EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        try {
            $aplazamiento = new AplazamientoSolicitud();
            $form = $this->createForm(AplazarSolicitudType::class, $aplazamiento);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['aplazar_solicitud']['fechaPropuestaAplazamiento']);
                $aplazamiento->setFechaPropuestaAplazamiento(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));
                $aplazamiento->setEstadoAplazamiento($estadoAplazamientoRepository->find(1));
                $aplazamiento->setSolicitud($solicitud);
                $aplazamientoSolicitudRepository->add($aplazamiento, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/plan_anual/aplazar.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/dictamen_comision", name="app_plan_anual_evaluacion_dictamen_comision", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function dictamenComision(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, SolicitudDictamenComisionRepository $solicitudDictamenComisionRepository)
    {
        try {
            $form = $this->createForm(SolicitudComisionType::class, null, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $nuevo = new SolicitudDictamenComision();
                if (!empty($form['dictamenComision']->getData())) {
                    $file = $form['dictamenComision']->getData();
                    $file_name = $_FILES['solicitud_comision']['name']['dictamenComision'];
                    $nuevo->setDictamenComision($file_name);
                    $file->move("uploads/evaluacion/plan_anual/comision/dictamen/", $file_name);
                }
                $nuevo->setSolicitud($solicitud);
                $solicitudDictamenComisionRepository->add($nuevo, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_anual_evaluacion_dictamen_comision', ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER);
            }
            $dictamentes = $solicitudDictamenComisionRepository->findBy(['solicitud' => $solicitud->getId()], ['id' => 'desc']);
            return $this->render('modules/evaluacion/plan_anual/dictamenComision.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud,
                'dictamentes' => $dictamentes
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_dictamen_comision', ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_dictamen", name="app_plan_anual_evaluacion_eliminar_dictamen", methods={"GET"})
     * @param Request $request
     * @param SolicitudDictamenComision $solicitudDictamenComision
     * @param SolicitudDictamenComisionRepository $solicitudDictamenComisionRepository
     * @return Response
     */
    public function eliminarDictamen(Request $request, SolicitudDictamenComision $solicitudDictamenComision, SolicitudDictamenComisionRepository $solicitudDictamenComisionRepository)
    {
        try {
            $solicitudDictamenComisionRepository->remove($solicitudDictamenComision, true);
            $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
            return $this->redirectToRoute('app_plan_anual_evaluacion_dictamen_comision', ['id' => $solicitudDictamenComision->getSolicitud()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_dictamen_comision', ['id' => $solicitudDictamenComision->getSolicitud()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/dictamen_cte", name="app_plan_anual_evaluacion_dictamen_cte", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function dictamenCTE(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $form = $this->createForm(SolicitudCTEType::class, $solicitud, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($form['dictamenCTE']->getData())) {
                    if ($solicitud->getDictamenCTE() != null) {
                        if (file_exists('uploads/evaluacion/plan_anual/cte/dictamen/' . $solicitud->getDictamenCTE())) {
                            unlink('uploads/evaluacion/plan_anual/cte/dictamen/' . $solicitud->getDictamenCTE());
                        }
                    }
                    $file = $form['dictamenCTE']->getData();
                    $file_name = $_FILES['solicitud_cte']['name']['dictamenCTE'];
                    $solicitud->setDictamenCTE($file_name);
                    $file->move("uploads/evaluacion/plan_anual/cte/dictamen/", $file_name);
                }
                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find(8));
                $solicitudRepository->edit($solicitud, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/plan_anual/dictamenCTE.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/dictamen_jan", name="app_plan_anual_evaluacion_dictamen_jan", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function dictamenJAN(Request                              $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository,
                                EstadoSolicitudRepository            $estadoSolicitudRepository, InstitucionRepository $institucionRepository,
                                SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository,
                                SolicitudProgramaRepository          $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(SolicitudJANType::class, $solicitud, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($form['dictamenJAN']->getData())) {
                    if ($solicitud->getDictamenJAN() != null) {
                        if (file_exists('uploads/evaluacion/plan_anual/jan/dictamen/' . $solicitud->getDictamenJAN())) {
                            unlink('uploads/evaluacion/plan_anual/jan/dictamen/' . $solicitud->getDictamenJAN());
                        }
                    }
                    $file = $form['dictamenJAN']->getData();
                    $file_name = $_FILES['solicitud_jan']['name']['dictamenJAN'];
                    $solicitud->setDictamenJAN($file_name);
                    $file->move("uploads/evaluacion/plan_anual/jan/dictamen/", $file_name);
                }
                $temp = explode('/', $request->request->all()['solicitud_jan']['fechaEmision']);
                $solicitud->setFechaEmision(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find(9));
                $solicitudRepository->edit($solicitud);

                if ('institucion' == $solicitud->getTipoSolicitud()) {
                    $solicitud->getInstitucion()->setCategoriaAcreditacion($solicitud->getCategoriaAcreditacionAlcanzada());
                    $institucionRepository->edit($solicitud->getInstitucion(), true);
                }
                if ('programa_pregrado' == $solicitud->getTipoSolicitud()) {
                    $solicitud->getProgramaPregrado()->setCategoriaAcreditacion($solicitud->getCategoriaAcreditacionAlcanzada());
                    $solicitudProgramaAcademicoRepository->edit($solicitud->getProgramaPregrado(), true);
                }
                if ('programa_posgrado' == $solicitud->getTipoSolicitud()) {
                    $solicitud->getProgramaPosgrado()->setCategoriaAcreditacion($solicitud->getCategoriaAcreditacionAlcanzada());
                    $solicitudProgramaRepository->edit($solicitud->getProgramaPosgrado(), true);
                }

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/plan_anual/dictamenJAN.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/eliminar", name="app_plan_anual_evaluacion_eliminar", methods={"GET"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function eliminar(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository)
    {
        try {
            if ($solicitudRepository->find($solicitud) instanceof Solicitud) {
                $solicitudRepository->remove($solicitud, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/iniciar_proceso", name="app_plan_anual_evaluacion_iniciar_proceso", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function iniciarProceso(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find(6));
            $solicitudRepository->edit($solicitud, true);
            $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/en_evaluacion", name="app_plan_anual_evaluacion_en_evaluacion", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function enEvaluacionPorElComiteTecnico(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find(7));
            $solicitudRepository->edit($solicitud, true);
            $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_anual_evaluacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
