<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Security\User;
use App\Form\Postgrado\CambioEstadoProgramaType;
use App\Form\Postgrado\ComisionProgramaType;
use App\Form\Postgrado\SolicitudProgramaType;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Services\TraceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/solicitud_programa")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class SolicitudProgramaController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_index", methods={"GET"})
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/postgrado/solicitud_programa/index.html.twig', [
            'registros' => $solicitudProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_solicitud_programa_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, TraceService $traceService, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository)
    {
        try {
            $solicitudPrograma = new SolicitudPrograma();
            $form = $this->createForm(SolicitudProgramaType::class, $solicitudPrograma, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if (!empty($_FILES['solicitud_programa']['name']['docPrograma'])) {
                    $file = $form['docPrograma']->getData();
                    $file_name = $_FILES['solicitud_programa']['name']['docPrograma'];
                    $solicitudPrograma->setDocPrograma($file_name);
                    $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(1));
                    $file->move("uploads/solicitud_programa", $file_name);
                }
                $solicitudProgramaRepository->add($solicitudPrograma, true);
                $traceService->registrar($this->getParameter('accion_registrar'), $this->getParameter('objeto_solicitud_programa'), null, \App\Services\DoctrineHelper::toArray($solicitudPrograma));


                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_solicitud_programa_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, TraceService $traceService, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $dataAnterior = \App\Services\DoctrineHelper::toArray($solicitudPrograma);
            $form = $this->createForm(SolicitudProgramaType::class, $solicitudPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if (!empty($_FILES['solicitud_programa']['name']['docPrograma'])) {
                    if ($solicitudPrograma->getDocPrograma() != null) {
                        if (file_exists('uploads/solicitud_programa/' . $solicitudPrograma->getDocPrograma())) {
                            unlink('uploads/solicitud_programa/' . $solicitudPrograma->getDocPrograma());
                        }
                    }
                    $file = $form['docPrograma']->getData();
                    $ext = explode('.', $_FILES['solicitud_programa']['name']['docPrograma']);
                    $file_name = uniqid() . '.' . end($ext);
                    $solicitudPrograma->setDocPrograma()($file_name);
                    $file->move("uploads/solicitud_programa", $file_name);
                }

                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                $traceService->registrar($this->getParameter('accion_modificar'), $this->getParameter('objeto_solicitud_programa'), $dataAnterior, \App\Services\DoctrineHelper::toArray($solicitudPrograma));


                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/edit.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_modificar', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_solicitud_programa_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function detail(Request $request, SolicitudPrograma $solicitudPrograma)
    {
        return $this->render('modules/postgrado/solicitud_programa/detail.html.twig', [
            'item' => $solicitudPrograma,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_solicitud_programa_eliminar", methods={"GET"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository, TraceService $traceService)
    {
        try {
            if ($solicitudProgramaRepository->find($solicitudPrograma) instanceof SolicitudPrograma) {
                $solicitudProgramaRepository->remove($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');

                $traceService->registrar($this->getParameter('accion_eliminar'), $this->getParameter('objeto_solicitud_programa'), null, \App\Services\DoctrineHelper::toArray($solicitudPrograma));
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/cambiar_estado", name="app_solicitud_programa_cambiar_estado", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function cambiarEstado(Request $request, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(CambioEstadoProgramaType::class, $solicitudPrograma);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $solicitudPrograma->setFechaProximaAcreditacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['cambio_estado_programa']['fechaProximaAcreditacion']));

                if (!empty($_FILES['cambio_estado_programa']['name']['resolucionPrograma'])) {
                    if ($solicitudPrograma->getResolucionPrograma() != null) {
                        if (file_exists('uploads/resolucion_programa/' . $solicitudPrograma->getResolucionPrograma())) {
                            unlink('uploads/resolucion_programa/' . $solicitudPrograma->getResolucionPrograma());
                        }
                    }

                    $file = $form['resolucionPrograma']->getData();
                    $ext = explode('.', $_FILES['cambio_estado_programa']['name']['resolucionPrograma']);
                    $file_name = $_FILES['cambio_estado_programa']['name']['resolucionPrograma'];
                    $solicitudPrograma->setResolucionPrograma($file_name);
                    $file->move("uploads/resolucion_programa", $file_name);
                }


                if ($solicitudPrograma->getEstadoPrograma()->getId() != 3) {
                    $solicitudPrograma->setCategoriaCategorizacion(null);
                    $solicitudPrograma->setAnnoAcreditacion(null);
                    $solicitudPrograma->setNivelAcreditacion(null);
                    $solicitudPrograma->setFechaProximaAcreditacion(null);
                    $solicitudPrograma->setDescripcion(null);
                    $solicitudPrograma->setResolucionPrograma(null);
                    $solicitudPrograma->setCodigoPrograma(null);
                }


                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/cambio_estado.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_cambiar_estado', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_comision", name="app_solicitud_programa_asignar_comision", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function asignarComision(Request $request, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(ComisionProgramaType::class, $solicitudPrograma);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/asignar_comision.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_asignar_comision', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }

}
