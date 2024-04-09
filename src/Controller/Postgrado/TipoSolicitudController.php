<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\TipoPrograma;
use App\Entity\Postgrado\TipoSolicitud;
use App\Entity\Postgrado\TipoSolicitudClasificacion;
use App\Entity\Security\User;
use App\Form\Postgrado\TipoProgramaType;
use App\Form\Postgrado\TipoSolicitudClasificacionType;
use App\Form\Postgrado\TipoSolicitudType;
use App\Repository\Postgrado\TipoProgramaRepository;
use App\Repository\Postgrado\TipoSolicitudClasificacionRepository;
use App\Repository\Postgrado\TipoSolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/tipo_solicitud")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_TIP_SOLICITUD_POST")
 */
class TipoSolicitudController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_solicitud_index", methods={"GET"})
     * @param TipoSolicitudRepository $tipoSolicitudRepository
     * @return Response
     */
    public function index(TipoSolicitudRepository $tipoSolicitudRepository, TipoSolicitudClasificacionRepository $tipoSolicitudClasificacionRepository)
    {
        $registros = $tipoSolicitudRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        $all = [];
        if (is_array($registros)) {
            foreach ($registros as $value) {
                $arrayClasificaciones = [];
                $clas = $tipoSolicitudClasificacionRepository->findBy(['tipoSolicitud' => $value->getId()]);
                if (is_array($clas)) {
                    foreach ($clas as $v) {
                        $arrayClasificaciones[] = $v->getClasificacion();
                    }
                }
                $value->clasificaciones = $arrayClasificaciones;
            }
        }
        return $this->render('modules/postgrado/tipo_solicitud/index.html.twig', [
            'registros' => $registros,
        ]);
    }

    /**
     * @Route("/registrar", name="app_tipo_solicitud_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoSolicitudRepository $tipoSolicitudRepository
     * @return Response
     */
    public function registrar(Request $request, TipoSolicitudRepository $tipoSolicitudRepository)
    {
        try {
            $tipoSolicitud = new TipoSolicitud();
            $form = $this->createForm(TipoSolicitudType::class, $tipoSolicitud, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoSolicitudRepository->add($tipoSolicitud, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/tipo_solicitud/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_solicitud_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_solicitud_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoSolicitud $tipoSolicitud
     * @param TipoSolicitudRepository $tipoSolicitudRepository
     * @return Response
     */
    public function modificar(Request $request, TipoSolicitud $tipoSolicitud, TipoSolicitudRepository $tipoSolicitudRepository)
    {
        try {
            $form = $this->createForm(TipoSolicitudType::class, $tipoSolicitud, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoSolicitudRepository->edit($tipoSolicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/tipo_solicitud/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_solicitud_modificar', ['id' => $tipoSolicitud], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_solicitud_detail", methods={"GET", "POST"})
     * @param TipoSolicitud $tipoSolicitud
     * @return Response
     */
    public function detail(TipoSolicitud $tipoSolicitud)
    {
        return $this->render('modules/postgrado/tipo_solicitud/detail.html.twig', [
            'item' => $tipoSolicitud,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_solicitud_eliminar", methods={"GET"})
     * @param TipoSolicitud $tipoSolicitud
     * @param TipoSolicitudRepository $tipoSolicitudRepository
     * @return Response
     */
    public function eliminar(TipoSolicitud $tipoSolicitud, TipoSolicitudRepository $tipoSolicitudRepository)
    {
        try {
            if ($tipoSolicitudRepository->find($tipoSolicitud) instanceof TipoSolicitud) {
                $tipoSolicitudRepository->remove($tipoSolicitud, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_solicitud_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/config_clasificacion", name="app_tipo_solicitud_config_clasificacion", methods={"GET", "POST"})
     * @param TipoSolicitud $tipoSolicitud
     * @return Response
     */
    public function configurarClasificacion(Request $request, TipoSolicitud $tipoSolicitud, TipoSolicitudClasificacionRepository $tipoSolicitudClasificacionRepository)
    {
        try {
            $TipoSolicitudClasificacion = new TipoSolicitudClasificacion();
            $form = $this->createForm(TipoSolicitudClasificacionType::class, $TipoSolicitudClasificacion, ['action' => 'registrar']);
            $post = $request->request->all();

            if (isset($post['tipo_solicitud_clasificacion']['clasificacion']) && !empty($post['tipo_solicitud_clasificacion']['clasificacion'])) {
                $TipoSolicitudClasificacion->setTipoSolicitud($tipoSolicitud);
                $TipoSolicitudClasificacion->setClasificacion($post['tipo_solicitud_clasificacion']['clasificacion']);
                $tipoSolicitudClasificacionRepository->add($TipoSolicitudClasificacion, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');

                return $this->redirectToRoute('app_tipo_solicitud_config_clasificacion', ['id' => $tipoSolicitud->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/tipo_solicitud/config_clasificacion.html.twig', [
                'form' => $form->createView(),
                'clasificaciones' => $tipoSolicitudClasificacionRepository->findBy(['tipoSolicitud' => $tipoSolicitud->getId()]),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_solicitud_config_clasificacion', ['id' => $tipoSolicitud->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_nivel", name="app_tipo_solicitud_eliminar_nivel", methods={"GET"})
     * @param TipoSolicitudClasificacion $TipoSolicitudClasificacion
     * @param TipoSolicitudClasificacionRepository $TipoSolicitudClasificacionRepository
     * @return Response
     */
    public function eliminarNivel(TipoSolicitudClasificacion $TipoSolicitudClasificacion, TipoSolicitudClasificacionRepository $TipoSolicitudClasificacionRepository)
    {
        try {
            if ($TipoSolicitudClasificacionRepository->find($TipoSolicitudClasificacion) instanceof TipoSolicitudClasificacion) {
                $TipoSolicitudClasificacionRepository->remove($TipoSolicitudClasificacion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_solicitud_config_clasificacion', ['id' => $TipoSolicitudClasificacion->getTipoSolicitud()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_solicitud_config_clasificacion', ['id' => $TipoSolicitudClasificacion->getTipoSolicitud()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_solicitud_config_clasificacion', ['id' => $TipoSolicitudClasificacion->getTipoSolicitud()->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
