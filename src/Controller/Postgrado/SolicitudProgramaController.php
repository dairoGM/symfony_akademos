<?php

namespace App\Controller\Postgrado;

use App\Entity\NotificacionesUsuario;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Postgrado\SolicitudProgramaComision;
use App\Entity\Postgrado\SolicitudProgramaDictamen;
use App\Entity\Security\User;
use App\Form\Postgrado\AprobarProgramaType;
use App\Form\Postgrado\CambioEstadoProgramaType;
use App\Form\Postgrado\ComisionProgramaType;
use App\Form\Postgrado\NoAprobarProgramaType;
use App\Form\Postgrado\SolicitudProgramaDictamenType;
use App\Form\Postgrado\SolicitudProgramaType;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\ComisionRepository;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\MiembrosComisionRepository;
use App\Repository\Postgrado\RolComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaDictamenRepository;
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
     * @param TraceService $traceService
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @param EstadoProgramaRepository $estadoProgramaRepository
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
     * @param TraceService $traceService
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
     * @param SolicitudPrograma $solicitudPrograma
     * @return Response
     */
    public function detail(SolicitudPrograma $solicitudPrograma)
    {
        return $this->render('modules/postgrado/solicitud_programa/detail.html.twig', [
            'item' => $solicitudPrograma,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_solicitud_programa_eliminar", methods={"GET"})
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @param TraceService $traceService
     * @return Response
     */
    public function eliminar(SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository, TraceService $traceService)
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
     * @Route("/{id}/aprobar", name="app_solicitud_programa_aprobar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaRepository $estadoProgramaRepository
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function aprobar(Request $request, EstadoProgramaRepository $estadoProgramaRepository, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(AprobarProgramaType::class, $solicitudPrograma);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $solicitudPrograma->setFechaProximaAcreditacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['aprobar_programa']['fechaProximaAcreditacion']));

                if (!empty($_FILES['aprobar_programa']['name']['resolucionPrograma'])) {
                    if ($solicitudPrograma->getResolucionPrograma() != null) {
                        if (file_exists('uploads/resolucion_programa/' . $solicitudPrograma->getResolucionPrograma())) {
                            unlink('uploads/resolucion_programa/' . $solicitudPrograma->getResolucionPrograma());
                        }
                    }

                    $file = $form['resolucionPrograma']->getData();
                    $ext = explode('.', $_FILES['aprobar_programa']['name']['resolucionPrograma']);
                    $file_name = $_FILES['aprobar_programa']['name']['resolucionPrograma'];
                    $solicitudPrograma->setResolucionPrograma($file_name);
                    $file->move("uploads/resolucion_programa", $file_name);
                }
                if (!empty($_FILES['aprobar_programa']['name']['dictamenFinal'])) {
                    if ($solicitudPrograma->getDictamenFinal() != null) {
                        if (file_exists('uploads/dictamen_final/' . $solicitudPrograma->getDictamenFinal())) {
                            unlink('uploads/dictamen_final/' . $solicitudPrograma->getDictamenFinal());
                        }
                    }

                    $file = $form['dictamenFinal']->getData();
                    $ext = explode('.', $_FILES['aprobar_programa']['name']['dictamenFinal']);
                    $file_name = $_FILES['aprobar_programa']['name']['dictamenFinal'];
                    $solicitudPrograma->setDictamenFinal($file_name);
                    $file->move("uploads/dictamen_final", $file_name);
                }

                $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(4));

                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/no-aprobar", name="app_solicitud_programa_no_aprobar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaRepository $estadoProgramaRepository
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function noAprobar(Request $request, EstadoProgramaRepository $estadoProgramaRepository, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(NoAprobarProgramaType::class, $solicitudPrograma);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['no_aprobar_programa']['name']['dictamenFinal'])) {
                    if ($solicitudPrograma->getDictamenFinal() != null) {
                        if (file_exists('uploads/dictamen_final/' . $solicitudPrograma->getDictamenFinal())) {
                            unlink('uploads/dictamen_final/' . $solicitudPrograma->getDictamenFinal());
                        }
                    }

                    $file = $form['dictamenFinal']->getData();
                    $ext = explode('.', $_FILES['no_aprobar_programa']['name']['dictamenFinal']);
                    $file_name = $_FILES['no_aprobar_programa']['name']['dictamenFinal'];
                    $solicitudPrograma->setDictamenFinal($file_name);
                    $file->move("uploads/dictamen_final", $file_name);
                }

                $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(5));

                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/no_aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_comision", name="app_solicitud_programa_asignar_comision", methods={"GET", "POST"})
     * @param Request $request
     * @param MiembrosComisionRepository $miembrosComisionRepository
     * @param NotificacionesUsuarioRepository $notificacionesUsuarioRepository
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @param EstadoProgramaRepository $estadoProgramaRepository
     * @param SolicitudPrograma $solicitudPrograma
     * @param ComisionRepository $comisionRepository
     * @param SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository
     * @return Response
     */
    public function asignarComision(Request $request, MiembrosComisionRepository $miembrosComisionRepository, NotificacionesUsuarioRepository $notificacionesUsuarioRepository, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudPrograma $solicitudPrograma, ComisionRepository $comisionRepository, SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository)
    {
        try {
            $form = $this->createForm(ComisionProgramaType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comisiones = $solicitudProgramaComisionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
                if (is_array($comisiones) && count($comisiones) > 0) {
                    foreach ($comisiones as $values) {
                        $solicitudProgramaComisionRepository->remove($values, true);
                    }
                }

                foreach ($request->request->all()['comision_programa']['comision'] as $value) {
                    $nueva = new SolicitudProgramaComision();
                    $nueva->setSolicitudPrograma($solicitudPrograma);
                    $nueva->setComision($comisionRepository->find($value));
                    $solicitudProgramaComisionRepository->add($nueva, true);

                    /*Notificacion*/
                    $miembros = $miembrosComisionRepository->findBy(['comision' => $value]);
                    if (is_array($miembros)) {
                        foreach ($miembros as $valueMiembros) {
                            $nuevaNotificacion = new NotificacionesUsuario();
                            $nuevaNotificacion->setUsuarioRecive($valueMiembros->getMiembro()->getUsuario());
                            $nuevaNotificacion->setLeido(false);
                            $nuevaNotificacion->setTexto('La solicitud del programa: ' . $solicitudPrograma->getNombre() . ' le ha sido asignada para revisión.');
                            $nuevaNotificacion->setUsuarioEnvia($this->getUser());
                            $notificacionesUsuarioRepository->add($nuevaNotificacion, true);
                        }
                    }
                }

                $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(2));
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }
            $temp = $solicitudProgramaComisionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            $comisionesAsignadas = [];
            foreach ($temp as $value) {
                $comisionesAsignadas[] = (string)$value->getComision()->getId();
            }
            $comisiones = implode(',', $comisionesAsignadas);

            return $this->render('modules/postgrado/solicitud_programa/asignar_comision.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'comisiones' => $comisiones
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asociar_dictamen", name="app_solicitud_programa_asociar_dictamen", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository
     * @return Response
     */
    public function asociarDictamen(Request $request, PersonaRepository $personaRepository, ComisionRepository $comisionRepository, RolComisionRepository $rolComisionRepository, SolicitudPrograma $solicitudPrograma, SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository, SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository)
    {
        try {
            $entidad = new SolicitudProgramaDictamen();
            $comisiones = $solicitudProgramaComisionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            $array = [];
            foreach ($comisiones as $values) {
                if (!in_array($values->getComision()->getId(), $array)) {
                    $array[] = $values->getComision()->getId();
                }
            }
            $choices = [
                'arrayIdsComision' => $array
            ];

            $form = $this->createForm(SolicitudProgramaDictamenType::class, $entidad, $choices);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId(), 'rolComision' => $request->request->all()['solicitud_programa_dictamen']['rolComision']]);
                if (empty($exist)) {
                    if (!empty($_FILES['solicitud_programa_dictamen']['name']['dictamen'])) {
                        $file = $form['dictamen']->getData();
                        $file_name = $_FILES['solicitud_programa_dictamen']['name']['dictamen'];
                        $entidad->setDictamen($file_name);
                        $file->move("uploads/solicitud_programa/dictamen", $file_name);
                    }


                    $entidad->setSolicitudPrograma($solicitudPrograma);
                    $entidad->setComision($comisionRepository->find($request->request->all()['solicitud_programa_dictamen']['comision']));
                    $entidad->setRolComision($rolComisionRepository->find($request->request->all()['solicitud_programa_dictamen']['rolComision']));
                    $solicitudProgramaDictamenRepository->add($entidad, true);


                    //validar que la cantidad de dictamenes asignados con el rol de jefe de comision sea igual que la cantidad de comision


                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/asociar_dictamen.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'personaAutenticada' => $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]),
                'registros' => $solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_dictamen", name="app_solicitud_programa_eliminar_dictamen", methods={"GET"})
     * @param Request $request
     * @param SolicitudProgramaDictamen $solicitudProgramaDictamen
     * @param SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository
     * @return Response
     */
    public function eliminarDictamen(Request $request, SolicitudProgramaDictamen $solicitudProgramaDictamen, SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository)
    {
        try {
            if ($solicitudProgramaDictamen instanceof SolicitudProgramaDictamen) {
                $solicitudProgramaDictamenRepository->remove($solicitudProgramaDictamen, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudProgramaDictamen->getSolicitudPrograma()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudProgramaDictamen->getSolicitudPrograma()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudProgramaDictamen->getSolicitudPrograma()->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
