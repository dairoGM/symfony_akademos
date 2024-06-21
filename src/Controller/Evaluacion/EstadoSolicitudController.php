<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Security\User;
use App\Form\Evaluacion\EstadoSolicitudType;
use App\Repository\Evaluacion\EstadoSolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/evaluacion/estado_solicitud")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTADO_SOLICITUD")
 */
class EstadoSolicitudController extends AbstractController
{

    /**
     * @Route("/", name="app_estado_solicitud_index", methods={"GET"})
     * @param EstadoSolicitudRepository $estadoSolicitudRepository
     * @return Response
     */
    public function index(EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        return $this->render('modules/evaluacion/estado_solicitud/index.html.twig', [
            'registros' => $estadoSolicitudRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_estado_solicitud_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoSolicitudRepository $estadoSolicitudRepository
     * @return Response
     */
    public function registrar(Request $request, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $catDocenteEntity = new EstadoSolicitud();
            $form = $this->createForm(EstadoSolicitudType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $estadoSolicitudRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/estado_solicitud/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_solicitud_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_estado_solicitud_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estadoSolicitud
     * @param EstadoSolicitudRepository $estadoSolicitudRepository
     * @return Response
     */
    public function modificar(Request $request, EstadoSolicitud $estadoSolicitud, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $form = $this->createForm(EstadoSolicitudType::class, $estadoSolicitud, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $estadoSolicitudRepository->edit($estadoSolicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/estado_solicitud/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_solicitud_modificar', ['id' => $estadoSolicitud], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_estado_solicitud_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estadoSolicitud
     * @param EstadoSolicitudRepository $estadoSolicitudRepository
     * @return Response
     */
    public function detail(Request $request, EstadoSolicitud $estadoSolicitud)
    {
        return $this->render('modules/evaluacion/estado_solicitud/detail.html.twig', [
            'item' => $estadoSolicitud,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_estado_solicitud_eliminar", methods={"GET"})
     * @param Request $request
     * @param EstadoSolicitud $estadoSolicitud
     * @param EstadoSolicitudRepository $estadoSolicitudRepository
     * @return Response
     */
    public function eliminar(Request $request, EstadoSolicitud $estadoSolicitud, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            if ($estadoSolicitudRepository->find($estadoSolicitud) instanceof EstadoSolicitud) {
                $estadoSolicitudRepository->remove($estadoSolicitud, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_estado_solicitud_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
