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
use App\Form\Evaluacion\SolicitudComisionType;
use App\Form\Evaluacion\SolicitudInformeAutoevaluacionType;
use App\Form\Evaluacion\SolicitudJANType;
use App\Form\Evaluacion\SolicitudSimplificadaType;
use App\Form\Evaluacion\SolicitudType;
use App\Repository\Evaluacion\ComisionRepository;
use App\Repository\Evaluacion\ConvocatoriaRepository;
use App\Repository\Evaluacion\EstadoSolicitudRepository;
use App\Repository\Evaluacion\SolicitudRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Repository\Security\UserRepository;
use App\Repository\Tramite\InstitucionExtranjeraRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/solicitud")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_SOLICITUD")
 */
class SolicitudController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_index", methods={"GET"})
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function index(SolicitudRepository $solicitudRepository)
    {
        return $this->render('modules/evaluacion/solicitud/index.html.twig', [
            'registros' => $solicitudRepository->findBy(['estadoSolicitud' => [1, 2]], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_solicitud_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function registrar(Request $request, SolicitudProgramaRepository $solicitudProgramaRepository, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository, InstitucionRepository $institucionRepository, SolicitudRepository $solicitudRepository, PersonaRepository $personaRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $solicitud = new Solicitud();
            $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
            $dataInst = $institucionRepository->findBy(['estructura' => $personaAutenticada->getEstructura()->getId()]);
            $idInstitucion = isset($dataInst[0]) ? $dataInst[0]->getId() : null;

            $form = $this->createForm(SolicitudType::class, $solicitud, ['cartaSolicitud' => 'registrar', 'idEstructuraPersonaAutenticada' => $personaAutenticada->getEstructura()->getId()]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {

                $params = $request->request->all()['solicitud'];

                $filtros['tipoSolicitud'] = $params['tipoSolicitud'];
                $filtros['convocatoria'] = $params['convocatoria'];
                if ('institucion' == $params['tipoSolicitud']) {
                    $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
                    if ($isAdmin) {
                        $filtros['institucion'] = $params['institucionesAdmin'];
                    } else {
                        $filtros['institucion'] = $params['institucion'];
                    }
                } elseif ('programa_pregrado' == $params['tipoSolicitud']) {
                    $filtros['programaPregrado'] = $params['programaPregrado'];
                } elseif ('programa_posgrado' == $params['tipoSolicitud']) {
                    $filtros['programaPosgrado'] = $params['programaPosgrado'];
                }

                $exist = $solicitudRepository->findBy($filtros);
                if (isset($exist[0])) {
                    $this->addFlash('error', 'Ya existe una solicitud registrada de ese tipo en la convocatoria seleccionada.');
                    return $this->redirectToRoute('app_solicitud_registrar', [], Response::HTTP_SEE_OTHER);
                }
                if (!empty($request->request->all()['solicitud']['fechaPropuesta'])) {
                    $solicitud->setFechaPropuesta(\DateTime::createFromFormat('d/m/Y', $request->request->all()['solicitud']['fechaPropuesta']));
                }
                if ('institucion' == $request->request->all()['solicitud']['tipoSolicitud']) {
                    if (!empty($request->request->all()['solicitud']['institucionesAdmin'])) {
                        $solicitud->setInstitucion($institucionRepository->find($request->request->all()['solicitud']['institucionesAdmin']));
                    } else {
                        $solicitud->setInstitucion($dataInst[0]);
                    }
                }
                if ('programa_pregrado' == $request->request->all()['solicitud']['tipoSolicitud']) {
                    $solicitud->setProgramaPregrado($solicitudProgramaAcademicoRepository->find($request->request->all()['solicitud']['programaPregrado']));
                }
                if ('programa_posgrado' == $request->request->all()['solicitud']['tipoSolicitud']) {
                    $solicitud->setProgramaPosgrado($solicitudProgramaRepository->find($request->request->all()['solicitud']['programaPosgrado']));
                }
                if (!empty($_FILES['solicitud']['name']['cartaSolicitud'])) {
                    $file = $form['cartaSolicitud']->getData();
                    $file_name = $_FILES['solicitud']['name']['cartaSolicitud'];
                    $solicitud->setCartaSolicitud($file_name);
                    $file->move("uploads/evaluacion/solicitud/cartaSolicitud", $file_name);
                }
                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_solicitada')));
                $solicitudRepository->add($solicitud, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }


            return $this->render('modules/evaluacion/solicitud/new.html.twig', [
                'form' => $form->createView(),
                'idInstitucion' => $idInstitucion
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_solicitud_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function modificar(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository)
    {
        try {
            $form = $this->createForm(SolicitudSimplificadaType::class, $solicitud, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $temp = explode('/', $request->request->all()['solicitud_simplificada']['fechaPropuesta']);
                $solicitud->setFechaPropuesta(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                if (!empty($form['cartaSolicitud']->getData())) {
                    if ($solicitud->getCartaSolicitud() != null) {
                        if (file_exists('uploads/evaluacion/solicitud/cartaSolicitud/' . $solicitud->getCartaSolicitud())) {
                            unlink('uploads/evaluacion/solicitud/cartaSolicitud/' . $solicitud->getCartaSolicitud());
                        }
                    }
                    $file = $form['cartaSolicitud']->getData();
                    $file_name = $_FILES['solicitud_simplificada']['name']['cartaSolicitud'];
                    $solicitud->setCartaSolicitud($file_name);
                    $file->move("uploads/evaluacion/solicitud/cartaSolicitud", $file_name);
                }

                $solicitudRepository->edit($solicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/solicitud/edit.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/aprobar", name="app_solicitud_aprobar", methods={"GET", "POST"})
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
                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_aceptada')));

                $solicitudRepository->edit($solicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/solicitud/aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/rechazar", name="app_solicitud_rechazar", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function rechazar(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, EstadoSolicitudRepository $estadoSolicitudRepository)
    {
        try {
            $form = $this->createForm(RechazarSolicitudType::class, $solicitud);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_rechazada')));
                $solicitudRepository->edit($solicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/solicitud/rechazar.html.twig', [
                'form' => $form->createView(),
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_solicitud_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @return Response
     */
    public function detail(Request $request, Solicitud $solicitud)
    {
        return $this->render('modules/evaluacion/solicitud/detail2.html.twig', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_solicitud_eliminar", methods={"GET"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, Solicitud $solicitud, SolicitudRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($solicitud) instanceof Solicitud) {
                $tipoProgramaRepository->remove($solicitud, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/cambiar_estado", name="app_solicitud_cambiar_estado", methods={"GET"})
     * @param Request $request
     * @param Solicitud $solicitud
     * @param SolicitudRepository $solicitudRepository
     * @return Response
     */
    public function cambiarEstado(Request $request, Solicitud $solicitud, EstadoSolicitudRepository $estadoSolicitudRepository, SolicitudRepository $solicitudRepository)
    {
        try {
            if ($solicitudRepository->find($solicitud) instanceof Solicitud) {
                $solicitud->setEstadoSolicitud($estadoSolicitudRepository->find($this->getParameter('estado_evaluacion_revision')));
                $solicitudRepository->edit($solicitud, true);
                $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/get_categoria_acreditacion_institucion", name="app_solicitud_get_categoria_acreditacion_institucion", methods={"GET", "POST"})
     * @param Request $request
     * @param InstitucionRepository $institucionRepository
     * @return Response
     */
    public function obtenerCategoriaAcreditacion(Request $request, InstitucionRepository $institucionRepository)
    {
        try {
            $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
            $arrayInstituciones = [];
            if ($isAdmin) {
                $arrayInstituciones = $institucionRepository->getInstituciones();
            }
            $institucionNombre = $categoriaAcreditacion = null;
            if (!empty($request->request->get('institucion'))) {
                $institucion = $institucionRepository->find($request->request->get('institucion'));
                $categoriaAcreditacion = is_object($institucion->getCategoriaAcreditacion()) ? $institucion->getCategoriaAcreditacion()->getNombre() : null;
                $institucionNombre = $institucion->getNombre();
            }

            return $this->json(['isAdmin' => $isAdmin, 'instituciones' => $arrayInstituciones, 'categoriaAcreditacion' => $categoriaAcreditacion, 'nombreInstitucion' => $institucionNombre]);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage());
        }
    }

    /**
     * @Route("/get_categoria_acreditacion_pregrado", name="app_solicitud_get_categoria_acreditacion_pregrado", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository
     * @return Response
     */
    public function obtenerCategoriaAcreditacionPregrado(Request $request, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        try {
            $pregrado = $solicitudProgramaAcademicoRepository->find($request->request->get('pregrado'));
            $categoriaAcreditacion = $pregrado->getCategoriaAcreditacion()->getNombre();
            return $this->json(['categoriaAcreditacion' => $categoriaAcreditacion]);
        } catch (\Exception $exception) {
            return $this->json(false);
        }
    }

    /**
     * @Route("/get_categoria_acreditacion_posgrado", name="app_solicitud_get_categoria_acreditacion_posgrado", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function obtenerCategoriaAcreditacionPosgrado(Request $request, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $postgrado = $solicitudProgramaRepository->find($request->request->get('posgrado'));
            $categoriaAcreditacion = is_object($postgrado->getCategoriaAcreditacion()) ? $postgrado->getCategoriaAcreditacion()->getNombre() : null;
            return $this->json(['categoriaAcreditacion' => $categoriaAcreditacion]);
        } catch (\Exception $exception) {
            return $this->json(false);
        }
    }

    /**
     * Add package entity.
     *
     * @Route("/get_programas_pregrado", name="app_solicitud_get_programas_pregrado", methods={"GET"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param InstitucionRepository $institucionRepository
     * @return JsonResponse
     */
    public function getProgramasPregrado(Request $request, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository, PersonaRepository $personaRepository, InstitucionRepository $institucionRepository): JsonResponse
    {
        try {
            $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
            $institucion = $institucionRepository->findBy(['estructura' => $personaAutenticada->getEstructura()->getId()]);
            $programasPregrado = [];

            $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
            $data = [];
            if ($isAdmin) {
                $data = $solicitudProgramaAcademicoInstitucionRepository->findAll();
            } else {
                if (isset($institucion[0])) {
                    $data = $solicitudProgramaAcademicoInstitucionRepository->findBy(['institucion' => $institucion[0]->getId()]);
                }
            }
            if (is_array($data)) {
                foreach ($data as $value) {
                    $item['id'] = $value->getSolicitudProgramaAcademico()->getId();
                    $item['nombre'] = $value->getSolicitudProgramaAcademico()->getNombre();
                    $programasPregrado[] = $item;
                }
            }

            return $this->json($programasPregrado);
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }

    /**
     * Add package entity.
     *
     * @Route("/get_programas_posgrado", name="app_solicitud_get_programas_posgrado", methods={"GET"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param InstitucionRepository $institucionRepository
     * @return JsonResponse
     */
    public function getProgramasPosgrado(Request $request, SolicitudProgramaRepository $solicitudProgramaRepository, PersonaRepository $personaRepository, InstitucionRepository $institucionRepository): JsonResponse
    {
        try {
            $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
            $institucion = $institucionRepository->findBy(['estructura' => $personaAutenticada->getEstructura()->getId()]);
            $programasPosgrado = [];
            $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
            $data = [];
            if ($isAdmin) {
                $data = $solicitudProgramaRepository->findBy(['estadoPrograma' => 7]);
            } else {
                if (isset($institucion[0])) {
                    $data = $solicitudProgramaRepository->findBy(['universidad' => $institucion[0]->getId(), 'estadoPrograma' => 7]);
                }
            }
            if (is_array($data)) {
                foreach ($data as $value) {
                    $item['id'] = $value->getId();
                    $item['nombre'] = $value->getNombre();
                    $programasPosgrado[] = $item;
                }
            }
            return $this->json($programasPosgrado);
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }

    /**
     * Add package entity.
     *
     * @Route("/get_convocatoria", name="app_solicitud_get_convocatoria", methods={"POST"})
     * @param Request $request
     * @param ConvocatoriaRepository $convocatoriaRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getConvocatoria(Request $request, ConvocatoriaRepository $convocatoriaRepository, Utils $utils): JsonResponse
    {
        try {
            $response = [];
            $convocatoria = $convocatoriaRepository->find($request->request->get('convocatoria'));
            $fechaInicio = $convocatoria->getFechaInicio()->format('Y-m-d');
            $fechaFin = $convocatoria->getFechaFin()->format('Y-m-d');
            $response = $utils->getFechasEntre($fechaInicio, $fechaFin);
            return $this->json($response);
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }


}
