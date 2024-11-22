<?php

namespace App\Controller\Postgrado;

use App\Entity\NotificacionesUsuario;
use App\Entity\Personal\Persona;
use App\Entity\Postgrado\PresencialidadPrograma;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Postgrado\SolicitudProgramaComision;
use App\Entity\Postgrado\SolicitudProgramaDictamen;
use App\Entity\Postgrado\SolicitudProgramaInstitucion;
use App\Entity\Postgrado\SolicitudProgramaPresencialidad;
use App\Entity\Postgrado\SolicitudProgramaVotacion;
use App\Entity\Security\User;
use App\Form\Postgrado\AprobarProgramaType;
use App\Form\Postgrado\CambioEstadoProgramaType;
use App\Form\Postgrado\ComisionProgramaType;
use App\Form\Postgrado\NoAprobarProgramaType;
use App\Form\Postgrado\RevisionDictamenType;
use App\Form\Postgrado\SolicitudProgramaDictamenType;
use App\Form\Postgrado\SolicitudProgramaType;
use App\Repository\Institucion\InstitucionFumRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\ComisionRepository;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\MiembrosComisionRepository;
use App\Repository\Postgrado\MiembrosCopepRepository;
use App\Repository\Postgrado\PresencialidadProgramaRepository;
use App\Repository\Postgrado\RolComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaDictamenRepository;
use App\Repository\Postgrado\SolicitudProgramaInstitucionRepository;
use App\Repository\Postgrado\SolicitudProgramaPresencialidadRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Repository\Postgrado\SolicitudProgramaVotacionRepository;
use App\Services\DoctrineHelper;
use App\Services\TraceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/solicitud_programa")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_SOLPROGRAM")
 */
class SolicitudProgramaController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_index", methods={"GET"})
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaRepository $solicitudProgramaRepository, PersonaRepository $personaRepository, MiembrosCopepRepository $miembrosCopepRepository)
    {
        $registros = $solicitudProgramaRepository->getSolicitudes();
        $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
        if (!$isAdmin) {
            $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
            $miembroCopep = $miembrosCopepRepository->getMiembroCopepDadoIdPersona($personaAutenticada->getId());
            //si es miembro de la copep activa
            if (!empty($miembroCopep)) {
                //listar todas las solicitudes donde no ha votado
                $registros = $solicitudProgramaRepository->getSolicitudesSinVotacion();
//                pr(count($registros));
            }
        }
        return $this->render('modules/postgrado/solicitud_programa/index.html.twig', [
            'registros' => $registros,
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
    public function registrar(Request $request, EntityManagerInterface $entityManager, PresencialidadProgramaRepository $presencialidadProgramaRepository, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, SolicitudProgramaInstitucionRepository $solicitudProgramaInstitucionRepository, InstitucionRepository $institucionRepository, TraceService $traceService, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository)
    {
        try {
            $solicitudPrograma = new SolicitudPrograma();
            $form = $this->createForm(SolicitudProgramaType::class, $solicitudPrograma, ['action' => 'registrar']);
            $form->handleRequest($request);
            $post = $request->request->all();

            if ($form->isSubmitted()) {
                if ($this->getParameter('id_tipo_solicitud_red') == $post['solicitud_programa']['tipoSolicitud']) {//tipo red
                    if (isset($post['solicitud_programa']['universidadesRed']) && count($post['solicitud_programa']['universidadesRed']) > 0) {
                        foreach ($post['solicitud_programa']['universidadesRed'] as $value) {
                            $item = new SolicitudProgramaInstitucion();
                            $item->setSolicitudPrograma($solicitudPrograma);
                            $item->setInstitucion($institucionRepository->find($value));
                            $solicitudProgramaInstitucionRepository->add($item);
                        }

                        if (isset($post['solicitud_programa']['universidad']) && is_array($post['solicitud_programa']['universidad']) && count($post['solicitud_programa']['universidad']) > 0) {
                            foreach ($post['solicitud_programa']['universidad'] as $value) {
                                $item = new SolicitudProgramaInstitucion();
                                $item->setSolicitudPrograma($solicitudPrograma);
                                $item->setInstitucion($institucionRepository->find($value));
                                $solicitudProgramaInstitucionRepository->add($item);
                            }
                        }

                    } else {
                        $this->addFlash('error', 'El campo Instituciones que intervienen es obligatorio.');
                        return $this->redirectToRoute('app_solicitud_programa_registrar', [], Response::HTTP_SEE_OTHER);
                    }
                } else if ($this->getParameter('id_tipo_solicitud_propio') == $post['solicitud_programa']['tipoSolicitud']) {//tipo propio
                    if ($this->getParameter('id_tipo_solicitud_clasificacion_exitente') == $post['solicitud_programa']['tipoSolicitudClasificacion']) {//clasificacion es existente
                        $existente = $solicitudProgramaRepository->find($post['solicitud_programa']['nombreExistente']);
                        $solicitudPrograma->setTipoPrograma($existente->getTipoPrograma());
                        $solicitudPrograma->setRamaCiencia($existente->getRamaCiencia());
                        $solicitudPrograma->setOriginalDe($existente->getOriginalDe());

                        if (!empty($post['solicitud_programa']['originalDe'])) {
                            $item = new SolicitudProgramaInstitucion();
                            $item->setSolicitudPrograma($solicitudPrograma);
                            $item->setInstitucion($institucionRepository->find($post['solicitud_programa']['originalDe']));
                            $solicitudProgramaInstitucionRepository->add($item);
                        } else {
//                            $this->addFlash('error', 'El campo Programa original de es obligatorio.');
//                            return $this->redirectToRoute('app_solicitud_programa_registrar', [], Response::HTTP_SEE_OTHER);
                        }
                    } else {//clasificacion es nuevo
                        $solicitudPrograma->setOriginalDe($solicitudPrograma->getUniversidad());
                    }
                }


                if (!empty($_FILES['solicitud_programa']['name']['docPrograma'])) {
                    $file = $form['docPrograma']->getData();
                    $file_name = $_FILES['solicitud_programa']['name']['docPrograma'];
                    $solicitudPrograma->setDocPrograma($file_name);
                    $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(1));
                    $file->move("uploads/postgrado/solicitud_programa", $file_name);
                }

                if (isset($post['solicitud_programa']['nombreExistente']) && !empty($post['solicitud_programa']['nombreExistente'])) {
                    $solicitudPrograma->setDescripcion($post['solicitud_programa']['nombreExistente']);
                }

                $solicitudProgramaRepository->add($solicitudPrograma, true);

                if (!empty($post['solicitud_programa']['presencialidadPrograma'])) {
                    foreach ($post['solicitud_programa']['presencialidadPrograma'] as $value) {
                        $presencialidad = new SolicitudProgramaPresencialidad();
                        $presencialidad->setSolicitudPrograma($solicitudPrograma);
                        $presencialidad->setPresencialidadPrograma($presencialidadProgramaRepository->find($value));
                        $entityManager->persist($presencialidad);
                    }
                }
                $entityManager->flush();

                $traceService->registrar($this->getParameter('accion_registrar'), $this->getParameter('objeto_solicitud_programa'), null, DoctrineHelper::toArray($solicitudPrograma));

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/new.html.twig', [
                'form' => $form->createView(),
                'id_tipo_solicitud_red' => $this->getParameter('id_tipo_solicitud_red'),
                'id_tipo_solicitud_propio' => $this->getParameter('id_tipo_solicitud_propio'),
                'id_tipo_solicitud_clasificacion_nuevo' => $this->getParameter('id_tipo_solicitud_clasificacion_nuevo'),
                'id_tipo_solicitud_clasificacion_exitente' => $this->getParameter('id_tipo_solicitud_clasificacion_exitente'),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
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
    public function modificar(Request $request, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, PresencialidadProgramaRepository $presencialidadProgramaRepository, EntityManagerInterface $entityManager, SolicitudProgramaInstitucionRepository $solicitudProgramaInstitucionRepository, InstitucionRepository $institucionRepository, TraceService $traceService, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $dataAnterior = DoctrineHelper::toArray($solicitudPrograma);
            $form = $this->createForm(SolicitudProgramaType::class, $solicitudPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);
            $post = $request->request->all();
            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getId();
            }

            if ($form->isSubmitted()) {
                $institucionIntervienen = $solicitudProgramaInstitucionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
                if (is_array($institucionIntervienen)) {
                    foreach ($institucionIntervienen as $value) {
                        $solicitudProgramaInstitucionRepository->remove($value, true);
                    }
                }

                if ($this->getParameter('id_tipo_solicitud_red') == $post['solicitud_programa']['tipoSolicitud']) {//tipo red
                    if (isset($post['solicitud_programa']['universidadesRed']) && count($post['solicitud_programa']['universidadesRed']) > 0) {

                        foreach ($post['solicitud_programa']['universidadesRed'] as $value) {
                            $item = new SolicitudProgramaInstitucion();
                            $item->setSolicitudPrograma($solicitudPrograma);
                            $item->setInstitucion($institucionRepository->find($value));
                            $solicitudProgramaInstitucionRepository->add($item);
                        }
                    } else {
                        $this->addFlash('error', 'El campo Instituciones que intervienen es obligatorio.');
                        return $this->redirectToRoute('app_solicitud_programa_registrar', [], Response::HTTP_SEE_OTHER);
                    }
                } else if ($this->getParameter('id_tipo_solicitud_propio') == $post['solicitud_programa']['tipoSolicitud']) {//tipo propio
                    if ($this->getParameter('id_tipo_solicitud_clasificacion_exitente') == $post['solicitud_programa']['tipoSolicitudClasificacion']) {//clasificacion es existente
                        if (!empty($post['solicitud_programa']['originalDe'])) {
                            $item = new SolicitudProgramaInstitucion();
                            $item->setSolicitudPrograma($solicitudPrograma);
                            $item->setInstitucion($institucionRepository->find($post['solicitud_programa']['originalDe']));
                            $solicitudProgramaInstitucionRepository->add($item);
                        }
                    }
                }

                if (!empty($_FILES['solicitud_programa']['name']['docPrograma'])) {
                    if ($solicitudPrograma->getDocPrograma() != null) {
                        if (file_exists('uploads/solicitud_programa/' . $solicitudPrograma->getDocPrograma())) {
                            unlink('uploads/postgrado/solicitud_programa/' . $solicitudPrograma->getDocPrograma());
                        }
                    }
                    $file = $form['docPrograma']->getData();
                    $file_name = $_FILES['solicitud_programa']['name']['docPrograma'];
                    $solicitudPrograma->setDocPrograma($file_name);
                    $file->move("uploads/solicitud_programa", $file_name);
                }
                if (isset($post['solicitud_programa']['nombreExistente']) && !empty($post['solicitud_programa']['nombreExistente'])) {
                    $solicitudPrograma->setDescripcion($post['solicitud_programa']['nombreExistente']);
                }

                if (!empty($post['solicitud_programa']['presencialidadPrograma'])) {
                    foreach ($presencialidades as $value) {
                        $solicitudProgramaPresencialidadRepository->remove($value, true);
                    }
                    foreach ($post['solicitud_programa']['presencialidadPrograma'] as $value) {
                        $presencialidad = new SolicitudProgramaPresencialidad();
                        $presencialidad->setSolicitudPrograma($solicitudPrograma);
                        $presencialidad->setPresencialidadPrograma($presencialidadProgramaRepository->find($value));
                        $entityManager->persist($presencialidad);
                        $entityManager->flush();
                    }
                }


                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $traceService->registrar($this->getParameter('accion_modificar'), $this->getParameter('objeto_solicitud_programa'), $dataAnterior, DoctrineHelper::toArray($solicitudPrograma));


                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }
            $institucionIntervienen = $solicitudProgramaInstitucionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            $arr = [];
            if (is_array($institucionIntervienen)) {
                foreach ($institucionIntervienen as $value) {
                    $arr[] = $value->getInstitucion()->getId();
                }
            }
            return $this->render('modules/postgrado/solicitud_programa/edit.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'presencialidades' => json_encode($presencialidadesArray),
                'institucionIntervienen' => json_encode($arr),
                'id_tipo_solicitud_red' => $this->getParameter('id_tipo_solicitud_red'),
                'id_tipo_solicitud_propio' => $this->getParameter('id_tipo_solicitud_propio'),
                'id_tipo_solicitud_clasificacion_nuevo' => $this->getParameter('id_tipo_solicitud_clasificacion_nuevo'),
                'id_tipo_solicitud_clasificacion_exitente' => $this->getParameter('id_tipo_solicitud_clasificacion_exitente')
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_solicitud_programa_detail", methods={"GET", "POST"})
     * @param SolicitudPrograma $solicitudPrograma
     * @return Response
     */
    public function detail(SolicitudPrograma $solicitudPrograma, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository)
    {
        $presencialidadesArray = [];
        $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
        foreach ($presencialidades as $v) {
            $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
        }

        return $this->render('modules/postgrado/solicitud_programa/detail.html.twig', [
            'item' => $solicitudPrograma,
            'presencialidades' => implode(", ", $presencialidadesArray)
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

                $traceService->registrar($this->getParameter('accion_eliminar'), $this->getParameter('objeto_solicitud_programa'), null, DoctrineHelper::toArray($solicitudPrograma));
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
    public function aprobar(Request $request, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $choices = [
                'resolucionPrograma' => empty($solicitudPrograma->getResolucionPrograma()) ? 'registrar' : 'modificar',
                'dictamenFinal' => empty($solicitudPrograma->getDictamenFinal()) ? 'registrar' : 'modificar'
            ];

            $form = $this->createForm(AprobarProgramaType::class, $solicitudPrograma, $choices);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $fechaProximaAcreditacion = $request->request->all()['aprobar_programa']['fechaProximaAcreditacion'] ?? null;
                if (!empty($fechaProximaAcreditacion)) {
                    $solicitudPrograma->setFechaProximaAcreditacion(\DateTime::createFromFormat('d/m/Y', $fechaProximaAcreditacion));
                }

                $fechaAprobacion = $request->request->all()['aprobar_programa']['fechaAprobacion'] ?? null;
                if (!empty($fechaAprobacion)) {
                    $solicitudPrograma->setFechaAprobacion(\DateTime::createFromFormat('d/m/Y', $fechaAprobacion));
                }

                if (!empty($_FILES['aprobar_programa']['name']['resolucionPrograma'])) {
                    if ($solicitudPrograma->getResolucionPrograma() != null) {
                        if (file_exists('uploads/postgrado/resolucion_programa/' . $solicitudPrograma->getResolucionPrograma())) {
                            unlink('uploads/postgrado/resolucion_programa/' . $solicitudPrograma->getResolucionPrograma());
                        }
                    }

                    $file = $form['resolucionPrograma']->getData();
                    $file_name = $_FILES['aprobar_programa']['name']['resolucionPrograma'];
                    $solicitudPrograma->setResolucionPrograma($file_name);
                    $file->move("uploads/postgrado/resolucion_programa", $file_name);
                }
                if (!empty($_FILES['aprobar_programa']['name']['dictamenFinal'])) {
                    if ($solicitudPrograma->getDictamenFinal() != null) {
                        if (file_exists('uploads/postgrado/dictamen_final/' . $solicitudPrograma->getDictamenFinal())) {
                            unlink('uploads/postgrado/dictamen_final/' . $solicitudPrograma->getDictamenFinal());
                        }
                    }

                    $file = $form['dictamenFinal']->getData();
                    $file_name = $_FILES['aprobar_programa']['name']['dictamenFinal'];
                    $solicitudPrograma->setDictamenFinal($file_name);
                    $file->move("uploads/postgrado/dictamen_final", $file_name);
                }

                $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(7));

                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_programas_aprobados_index', [], Response::HTTP_SEE_OTHER);
            }


            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
            }


            return $this->render('modules/postgrado/solicitud_programa/aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'presencialidades' => implode(", ", $presencialidadesArray)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_programas_aprobados_index', [], Response::HTTP_SEE_OTHER);
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
    public function noAprobar(Request $request, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(NoAprobarProgramaType::class, $solicitudPrograma);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['no_aprobar_programa']['name']['dictamenFinal'])) {
                    if ($solicitudPrograma->getDictamenFinal() != null) {
                        if (file_exists('uploads/postgrado/dictamen_final/' . $solicitudPrograma->getDictamenFinal())) {
                            unlink('uploads/postgrado/dictamen_final/' . $solicitudPrograma->getDictamenFinal());
                        }
                    }

                    $file = $form['dictamenFinal']->getData();
                    $file_name = $_FILES['no_aprobar_programa']['name']['dictamenFinal'];
                    $solicitudPrograma->setDictamenFinal($file_name);
                    $file->move("uploads/postgrado/dictamen_final", $file_name);
                }

                $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(5));

                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }
            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
            }
            return $this->render('modules/postgrado/solicitud_programa/no_aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'presencialidades' => implode(", ", $presencialidadesArray)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/{tipo}/asignar_comision", name="app_solicitud_programa_asignar_comision", methods={"GET", "POST"})
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
    public function asignarComision(Request $request, $tipo = '1', MiembrosComisionRepository $miembrosComisionRepository, NotificacionesUsuarioRepository $notificacionesUsuarioRepository, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudPrograma $solicitudPrograma, ComisionRepository $comisionRepository, SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository)
    {
        try {
            $form = $this->createForm(ComisionProgramaType::class, null, ['idTipoComision' => $tipo]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comisiones = $solicitudProgramaComisionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
                if (is_array($comisiones) && count($comisiones) > 0) {
                    foreach ($comisiones as $values) {
                        if (1 == $values->getComision()->getTipoComision()->getId()) {
                            $solicitudProgramaComisionRepository->remove($values, true);
                        }
                    }
                }

                foreach ($request->request->all()['comision_programa']['comision'] as $value) {
                    $nueva = new SolicitudProgramaComision();
                    $nueva->setSolicitudPrograma($solicitudPrograma);
                    $nueva->setComision($comisionRepository->find($value));
                    $solicitudProgramaComisionRepository->add($nueva, true);

                    if (1 == $tipo) {//si la comision es estandar
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
                        $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(2));
                    }

                }

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

            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
            }

            return $this->render('modules/postgrado/solicitud_programa/asignar_comision.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'comisiones' => $comisiones,
                'presencialidades' => implode(", ", $presencialidadesArray)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asociar_dictamen", name="app_solicitud_programa_asociar_dictamen", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository
     * @param PersonaRepository $personaRepository
     * @param ComisionRepository $comisionRepository
     * @param RolComisionRepository $rolComisionRepository
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository
     * @param SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository
     * @return Response
     */
    public function asociarDictamen(Request $request, MiembrosCopepRepository $miembrosCopepRepository, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, NotificacionesUsuarioRepository $notificacionesUsuarioRepository, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository, PersonaRepository $personaRepository, ComisionRepository $comisionRepository, RolComisionRepository $rolComisionRepository, SolicitudPrograma $solicitudPrograma, SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository)
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
            $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId(), 'rolComision' => $request->request->all()['solicitud_programa_dictamen']['rolComision'], 'comision' => $request->request->all()['solicitud_programa_dictamen']['comision']]);
                if (empty($exist)) {
                    if (!empty($_FILES['solicitud_programa_dictamen']['name']['dictamen'])) {
                        $file = $form['dictamen']->getData();
                        $file_name = $_FILES['solicitud_programa_dictamen']['name']['dictamen'];
                        $entidad->setDictamen($file_name);
                        $file->move("uploads/postgrado/solicitud_programa/dictamen", $file_name);
                    }


                    $entidad->setPropietarioDictamen($personaAutenticada);
                    $entidad->setSolicitudPrograma($solicitudPrograma);
                    $entidad->setComision($comisionRepository->find($request->request->all()['solicitud_programa_dictamen']['comision']));
                    $entidad->setRolComision($rolComisionRepository->find($request->request->all()['solicitud_programa_dictamen']['rolComision']));
                    $solicitudProgramaDictamenRepository->add($entidad, true);

                    //validar que la cantidad de dictamenes asignados con el rol de jefe de comision sea igual que la cantidad de comision
                    $cantidadComisiones = count($solicitudProgramaComisionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]));
                    $cantidadDictamenRolJefe = count($solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId(), 'rolComision' => 1]));

                    if ($cantidadComisiones == $cantidadDictamenRolJefe) {
                        $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(3)); //Analizado por comisión
                        $solicitudProgramaRepository->edit($solicitudPrograma, true);
                    }
                    /*Notificacion (Notificar a la presidenta y al secretario del la copep)*/
                    $presidentaSecretarioCopep = $miembrosCopepRepository->getPresidenteSecretario([1, 2]);
                    foreach ($presidentaSecretarioCopep as $valueMiembros) {
                        $nuevaNotificacion = new NotificacionesUsuario();
                        $nuevaNotificacion->setUsuarioRecive($valueMiembros->getMiembro()->getUsuario());
                        $nuevaNotificacion->setLeido(false);
                        $nuevaNotificacion->setTexto('La solicitud del programa: ' . $solicitudPrograma->getNombre() . ' ya tiene todos los dictamenes correspondiente y estan listos para revisar.');
                        $nuevaNotificacion->setUsuarioEnvia($this->getUser());
                        $notificacionesUsuarioRepository->add($nuevaNotificacion, true);
                    }


                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_solicitud_programa_asociar_dictamen', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
            }
            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
            }
            return $this->render('modules/postgrado/solicitud_programa/asociar_dictamen.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'personaAutenticada' => $personaAutenticada,
                'registros' => $solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]),
                'presencialidades' => implode(", ", $presencialidadesArray)
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
    public function eliminarDictamen(Request $request, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudProgramaComisionRepository $solicitudProgramaComisionRepository, SolicitudProgramaDictamen $solicitudProgramaDictamen, SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository)
    {
        try {
            if ($solicitudProgramaDictamen instanceof SolicitudProgramaDictamen) {
                $solicitudProgramaDictamenRepository->remove($solicitudProgramaDictamen, true);

                $cantidadComisiones = count($solicitudProgramaComisionRepository->findBy(['solicitudPrograma' => $solicitudProgramaDictamen->getSolicitudPrograma()->getId()]));
                $cantidadDictamenRolJefe = count($solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudProgramaDictamen->getSolicitudPrograma()->getId(), 'rolComision' => 1]));

                if ($cantidadComisiones != $cantidadDictamenRolJefe) {
                    $solicitudProgramaDictamen->getSolicitudPrograma()->setEstadoPrograma($estadoProgramaRepository->find(2));
                    $solicitudProgramaRepository->edit($solicitudProgramaDictamen->getSolicitudPrograma(), true);
                }

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


    /**
     * @Route("/{id}/revisar_dictamenes", name="app_solicitud_programa_revisar_dictamenes", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @return Response
     */
    public function revisionDictamen(Request $request, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, SolicitudProgramaDictamenRepository $solicitudProgramaDictamenRepository, EstadoProgramaRepository $estadoProgramaRepository, SolicitudProgramaRepository $solicitudProgramaRepository, NotificacionesUsuarioRepository $notificacionesUsuarioRepository, SolicitudPrograma $solicitudPrograma, MiembrosCopepRepository $miembrosCopepRepository)
    {
        try {
            $form = $this->createForm(RevisionDictamenType::class, $solicitudPrograma);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['revision_dictamen']['name']['dictamenGeneral'])) {
                    $file = $form['dictamenGeneral']->getData();
                    $file_name = $_FILES['revision_dictamen']['name']['dictamenGeneral'];
                    $solicitudPrograma->setDictamenFinal($file_name);
                    $file->move("uploads/postgrado/solicitud_programa/dictamen_general", $file_name);
                }

                /*Notificacion*/
                $presidentaSecretarioCopep = $miembrosCopepRepository->getMiembros();
                foreach ($presidentaSecretarioCopep as $valueMiembros) {
                    $nuevaNotificacion = new NotificacionesUsuario();
                    $nuevaNotificacion->setUsuarioRecive($valueMiembros->getMiembro()->getUsuario());
                    $nuevaNotificacion->setLeido(false);
                    $nuevaNotificacion->setTexto('La solicitud del programa: ' . $solicitudPrograma->getNombre() . ' esta lista para someter a votación.');
                    $nuevaNotificacion->setUsuarioEnvia($this->getUser());
                    $notificacionesUsuarioRepository->add($nuevaNotificacion, true);
                }

                $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(4));//Revisado
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);

            }
            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
            }
            return $this->render('modules/postgrado/solicitud_programa/revisar_dictamen.html.twig', ['form' => $form->createView(),
                    'solicitudPrograma' => $solicitudPrograma,
                    'registros' => $solicitudProgramaDictamenRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]),
                    'presencialidades' => implode(", ", $presencialidadesArray)
                ]
            );
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_revisar_dictamenes', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/votacion", name="app_solicitud_programa_votacion", methods={"GET", "POST"})
     * @param SolicitudProgramaVotacionRepository $solicitudProgramaVotacionRepository
     * @param SolicitudPrograma $solicitudPrograma
     * @return Response
     */
    public function votacion(SolicitudProgramaVotacionRepository $solicitudProgramaVotacionRepository, SolicitudProgramaPresencialidadRepository $solicitudProgramaPresencialidadRepository, PersonaRepository $personaRepository, SolicitudPrograma $solicitudPrograma, MiembrosCopepRepository $miembrosCopepRepository)
    {
        try {
            $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
            $miembroCopep = $miembrosCopepRepository->getMiembroCopepDadoIdPersona($personaAutenticada->getId());
            $presencialidadesArray = [];
            $presencialidades = $solicitudProgramaPresencialidadRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            foreach ($presencialidades as $v) {
                $presencialidadesArray[] = $v->getPresencialidadPrograma()->getNombre();
            }

            return $this->render('modules/postgrado/solicitud_programa/votacion.html.twig', [
                    'solicitudPrograma' => $solicitudPrograma,
                    'miembroCopep' => $miembroCopep,
//                    'existeVoto' => $solicitudProgramaVotacionRepository->existeVoto($personaAutenticada->getId()),
                    'registros' => $solicitudProgramaVotacionRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()], ['creado' => 'desc']),
                    'presencialidades' => implode(", ", $presencialidadesArray)
                ]
            );
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_votacion', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/votar", name="app_solicitud_programa_votacion_guardar", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function votar(Request $request, EstadoProgramaRepository $estadoProgramaRepository, SolicitudProgramaVotacionRepository $solicitudProgramaVotacionRepository, SolicitudProgramaRepository $solicitudProgramaRepository, MiembrosCopepRepository $miembrosCopepRepository)
    {
        try {
            $allPost = $request->request->all();
            $existe = $solicitudProgramaVotacionRepository->findBy(['miembrosCopep' => $allPost['miembroCopep'], 'solicitudPrograma' => $allPost['solicitudPrograma']]);

            if (isset($existe[0])) {
                $solicitudProgramaVotacionRepository->remove($existe[0], true);
            }

            $solicitudProgramaEntidad = $solicitudProgramaRepository->find($allPost['solicitudPrograma']);
            $nuevo = new SolicitudProgramaVotacion();
            $nuevo->setSolicitudPrograma($solicitudProgramaEntidad);
            $nuevo->setVoto(boolval($allPost['voto']));
            $nuevo->setMiembrosCopep($miembrosCopepRepository->find($allPost['miembroCopep']));
            $solicitudProgramaVotacionRepository->add($nuevo, true);


            $cantidadMiembrosCopep = count($miembrosCopepRepository->getMiembros());
            $cantidadVotosSi = count($solicitudProgramaVotacionRepository->findBy(['solicitudPrograma' => $allPost['solicitudPrograma'], 'voto' => true]));
            $cantidadVotosNo = count($solicitudProgramaVotacionRepository->findBy(['solicitudPrograma' => $allPost['solicitudPrograma'], 'voto' => false]));


            if (($cantidadVotosSi > ($cantidadMiembrosCopep / 2) + 1) || $cantidadVotosSi == $cantidadMiembrosCopep) {
                $solicitudProgramaEntidad->setEstadoPrograma($estadoProgramaRepository->find(5));// 'Pendiente de aprobación'
                $solicitudProgramaRepository->edit($solicitudProgramaEntidad, true);

            } else if (($cantidadVotosNo > ($cantidadMiembrosCopep / 2) + 1) || $cantidadVotosNo == $cantidadMiembrosCopep) {
                $solicitudProgramaEntidad->setEstadoPrograma($estadoProgramaRepository->find(6));// 'Rechazado'
                $solicitudProgramaRepository->edit($solicitudProgramaEntidad, true);
            }


            return $this->json(true);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage());
        }
    }


    /**
     * @Route("/get-programa-detalles", name="app_solicitud_programa_get_programa", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function asociarPersona(Request $request, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $idPrograma = $request->request->get('id_programa');
            $solicitud = $solicitudProgramaRepository->find($idPrograma);
            $data['tipoPrograma'] = $solicitud->getTipoPrograma()->getId();
            $data['originalDe'] = method_exists($solicitud->getOriginalDe(), 'getId') ? $solicitud->getOriginalDe()->getId() : null;
            $data['ramaCiencia'] = $solicitud->getRamaCiencia()->getId();
            return $this->json($data);
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }
}



