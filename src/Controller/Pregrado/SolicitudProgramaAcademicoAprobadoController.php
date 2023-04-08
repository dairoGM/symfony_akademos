<?php

namespace App\Controller\Pregrado;

use App\Entity\NotificacionesUsuario;
use App\Entity\Pregrado\ProgramaAcademicoDesactivado;
use App\Entity\Pregrado\ProgramaAcademicoReabierto;
use App\Entity\Pregrado\ProgramaAcademicoReabiertoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\ProgramaAcademicoDesactivadoType;
use App\Form\Pregrado\ProgramaAcademicoReabiertoType;
use App\Form\Pregrado\SolicitudProgramaComisionType;
use App\Form\Pregrado\SolicitudProgramaInstitucionType;
use App\Form\Pregrado\SolicitudProgramaPlanEstudioType;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
use App\Repository\Pregrado\MiembrosComisionNacionalRepository;
use App\Repository\Pregrado\ModificacionPlanEstudioRepository;
use App\Repository\Pregrado\ProgramaAcademicoDesactivadoRepository;
use App\Repository\Pregrado\ProgramaAcademicoReabiertoInstitucionRepository;
use App\Repository\Pregrado\ProgramaAcademicoReabiertoRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoComisionNacionalRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoPlanEstudioRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/solicitud_programa_academico_aprobado")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class SolicitudProgramaAcademicoAprobadoController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_academico_aprobado_index", methods={"GET"})
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/index.html.twig', [
            'registros' => $solicitudProgramaRepository->getSolicitudProgramaAcademicoAprobado([2,4, 5, 7, 8]),
        ]);
    }

    /**
     *
     * /**
     * @Route("/{id}/detail", name="app_solicitud_programa_academico_aprobado_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @return Response
     */
    public function detail(Request $request, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository, ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        $planEstudio = $solicitudProgramaAcademicoPlanEstudioRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]);
        $planEstudioAsociado = -1;
        if (is_array($planEstudio) && count($planEstudio) > 0) {
            $planEstudioAsociado = $planEstudio[0]->getPlanEstudio()->getId();
        }
        return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/detail.html.twig', [
            'item' => $solicitudProgramaAcademico,
            'format' => 'col2',
            'universidades' => $solicitudProgramaAcademicoInstitucionRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]),
            'modificacionesPlanEstudio' => $modificacionPlanEstudioRepository->findBy(['planEstudio' => $planEstudioAsociado])
        ]);
    }


    /**
     * @Route("/{id}/asignar_universidad", name="app_solicitud_programa_academico_aprobado_asignar_universidad", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return Response
     */
    public function asignarUniversidad(Request $request, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        try {
            $entidad = new SolicitudProgramaAcademicoInstitucion();
            $form = $this->createForm(SolicitudProgramaInstitucionType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $solicitudProgramaAcademicoInstitucionRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId(), 'institucion' => $request->request->all()['solicitud_programa_institucion']['institucion']]);
                if (empty($exist)) {
                    $entidad->setSolicitudProgramaAcademico($solicitudProgramaAcademico);
                    $solicitudProgramaAcademicoInstitucionRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/asignar_universidad.html.twig', [
                'form' => $form->createView(),
                'solicitudProgramaAcademico' => $solicitudProgramaAcademico,
                'registros' => $solicitudProgramaAcademicoInstitucionRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_universidad", name="app_solicitud_programa_academico_aprobado_eliminar_universidad", methods={"GET"})
     * @param Request $request
     * @param SolicitudProgramaAcademicoInstitucion $solicitudProgramaAcademicoInstitucion
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return Response
     */
    public function eliminarUniversidad(Request $request, SolicitudProgramaAcademicoInstitucion $solicitudProgramaAcademicoInstitucion, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        try {
            if ($solicitudProgramaAcademicoInstitucion instanceof SolicitudProgramaAcademicoInstitucion) {
                $solicitudProgramaAcademicoInstitucionRepository->remove($solicitudProgramaAcademicoInstitucion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademicoInstitucion->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademicoInstitucion->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademicoInstitucion->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_plan_estudio", name="app_solicitud_programa_academico_aprobado_asignar_plan_estudio", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @param SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository
     * @return Response
     */
    public function asignarPlanEstudio(Request $request, Utils $utils, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        try {
            $entidad = new SolicitudProgramaAcademicoPlanEstudio();
            $form = $this->createForm(SolicitudProgramaPlanEstudioType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $solicitudProgramaAcademicoPlanEstudioRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId(), 'planEstudio' => $request->request->all()['solicitud_programa_plan_estudio']['planEstudio']]);
                if (empty($exist)) {
                    $entidad->setSolicitudProgramaAcademico($solicitudProgramaAcademico);
                    $solicitudProgramaAcademicoPlanEstudioRepository->add($entidad, true);
                    $solicitudProgramaAcademico->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(5));

                    $utils->guardarHistoricoEstadoProgramaAcademico($entidad->getSolicitudProgramaAcademico()->getId(), 5);

                    $solicitudProgramaAcademicoRepository->edit($solicitudProgramaAcademico);
                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_plan_estudio', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_plan_estudio', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/asignar_plan_estudio.html.twig', [
                'form' => $form->createView(),
                'solicitudProgramaAcademico' => $solicitudProgramaAcademico,
                'registros' => $solicitudProgramaAcademicoPlanEstudioRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_plan_estudio', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_plan_estudio", name="app_solicitud_programa_academico_aprobado_eliminar_plan_estudio", methods={"GET"})
     * @param SolicitudProgramaAcademicoPlanEstudio $solicitudProgramaAcademicoPlanEstudio
     * @param SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository
     * @return Response
     */
    public function eliminarPlanEstudio(Request $request, Utils $utils, SolicitudProgramaAcademicoPlanEstudio $solicitudProgramaAcademicoPlanEstudio, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        try {
            if ($solicitudProgramaAcademicoPlanEstudio instanceof SolicitudProgramaAcademicoPlanEstudio) {
                $solicitudProgramaAcademicoPlanEstudioRepository->remove($solicitudProgramaAcademicoPlanEstudio, true);

                $solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(2));

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->getId(), 5);

                $solicitudProgramaAcademicoRepository->edit($solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico());

                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_plan_estudio', ['id' => $solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_plan_estudio', ['id' => $solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_plan_estudio', ['id' => $solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_comision", name="app_solicitud_programa_academico_aprobado_asignar_comision", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @param SolicitudProgramaAcademicoComisionNacionalRepository $solicitudProgramaAcademicoComisionNacionalRepository
     * @return Response
     */
    public function asignarComision(Request $request, Utils $utils, MiembrosComisionNacionalRepository $miembrosComisionNacionalRepository, NotificacionesUsuarioRepository $notificacionesUsuarioRepository, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoComisionNacionalRepository $solicitudProgramaAcademicoComisionNacionalRepository, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        try {
            $entidad = new SolicitudProgramaAcademicoComisionNacional();
            $form = $this->createForm(SolicitudProgramaComisionType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $solicitudProgramaAcademicoComisionNacionalRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId(), 'comisionNacional' => $request->request->all()['solicitud_programa_comision']['comisionNacional']]);
                if (empty($exist)) {
                    $entidad->setSolicitudProgramaAcademico($solicitudProgramaAcademico);
                    $solicitudProgramaAcademicoComisionNacionalRepository->add($entidad, true);

                    $solicitudProgramaAcademico->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(4));

                    $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademico->getId(), 4);

                    $solicitudProgramaAcademicoRepository->edit($solicitudProgramaAcademico);

                    //Falta Notificar a los miembros de la comision nacional
                    $miembrosComisionNacional = $miembrosComisionNacionalRepository->findBy(['comision' => $request->request->all()['solicitud_programa_comision']['comisionNacional']]);
                    foreach ($miembrosComisionNacional as $valueMiembros) {
                        $nuevaNotificacion = new NotificacionesUsuario();
                        $nuevaNotificacion->setUsuarioRecive($valueMiembros->getMiembro()->getUsuario());
                        $nuevaNotificacion->setLeido(false);
                        $nuevaNotificacion->setTexto('La solicitud del programa: ' . $solicitudProgramaAcademico->getNombre() . ' ya está asignada a comisión.');
                        $nuevaNotificacion->setUsuarioEnvia($this->getUser());
                        $notificacionesUsuarioRepository->add($nuevaNotificacion, true);
                    }
                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_comision', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_comision', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/asignar_comision.html.twig', [
                'form' => $form->createView(),
                'solicitudProgramaAcademico' => $solicitudProgramaAcademico,
                'registros' => $solicitudProgramaAcademicoComisionNacionalRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_comision', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_comision", name="app_solicitud_programa_academico_aprobado_eliminar_comision", methods={"GET"})
     * @param SolicitudProgramaAcademicoComisionNacional $solicitudProgramaAcademicoComisionNacional
     * @param SolicitudProgramaAcademicoComisionNacionalRepository $solicitudProgramaAcademicoComisionNacionalRepository
     * @return Response
     */
    public function eliminarComision(Utils $utils, SolicitudProgramaAcademicoComisionNacional $solicitudProgramaAcademicoComisionNacional, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository, SolicitudProgramaAcademicoComisionNacionalRepository $solicitudProgramaAcademicoComisionNacionalRepository)
    {
        try {
            if ($solicitudProgramaAcademicoComisionNacional instanceof SolicitudProgramaAcademicoComisionNacional) {
                $solicitudProgramaAcademicoComisionNacional->getSolicitudProgramaAcademico()->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(2));

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademicoComisionNacional->getSolicitudProgramaAcademico()->getId(), 2);

                $solicitudProgramaAcademicoComisionNacionalRepository->remove($solicitudProgramaAcademicoComisionNacional, true);

                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_comision', ['id' => $solicitudProgramaAcademicoComisionNacional->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_comision', ['id' => $solicitudProgramaAcademicoComisionNacional->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_comision', ['id' => $solicitudProgramaAcademicoComisionNacional->getSolicitudProgramaAcademico()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/reabrir", name="app_solicitud_programa_academico_aprobado_reabrir", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaAcademicoRepository $estadoProgramaRepository
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function reabrir(Request $request, Utils $utils, EstadoProgramaAcademicoRepository $estadoProgramaRepository, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, ProgramaAcademicoReabiertoRepository $programaAcademicoReabiertoRepository, ProgramaAcademicoReabiertoInstitucionRepository $programaAcademicoReabiertoInstitucionRepository, InstitucionRepository $institucionRepository)
    {
        try {
            $reabierto = $programaAcademicoReabiertoRepository->findBy(['solicitudProgramaAcademico' => $solicitudPrograma->getId()]);

            $choices = [
                'fundamentacion' => !isset($reabierto[0]) > 0 ? 'registrar' : 'modificar',
                'dictamenDgp' => !isset($reabierto[0]) > 0 ? 'registrar' : 'modificar',
            ];
            $programaAcademicoReabierto = isset($reabierto[0]) ? $reabierto[0] : new ProgramaAcademicoReabierto();

            $form = $this->createForm(ProgramaAcademicoReabiertoType::class, $programaAcademicoReabierto, $choices);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $programaAcademicoReabierto->setSolicitudProgramaAcademico($solicitudPrograma);
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaRepository->find(8));
                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudPrograma->getId(), 8);
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                if (!empty($_FILES['programa_academico_reabierto']['name']['fundamentacion'])) {
                    if ($programaAcademicoReabierto->getFundamentacion() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/fundamentacion/' . $programaAcademicoReabierto->getFundamentacion())) {
                            unlink('uploads/pregrado/programas_aprobados/fundamentacion/' . $programaAcademicoReabierto->getFundamentacion());
                        }
                    }

                    $file = $form['fundamentacion']->getData();
                    $file_name = $_FILES['programa_academico_reabierto']['name']['fundamentacion'];
                    $programaAcademicoReabierto->setFundamentacion($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/fundamentacion/", $file_name);
                }
                if (!empty($_FILES['programa_academico_reabierto']['name']['dictamenDgp'])) {
                    if ($programaAcademicoReabierto->getDictamenDgp() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/dictamenDgp/' . $programaAcademicoReabierto->getDictamenDgp())) {
                            unlink('uploads/pregrado/programas_aprobados/dictamenDgp/' . $programaAcademicoReabierto->getDictamenDgp());
                        }
                    }

                    $file = $form['dictamenDgp']->getData();
                    $file_name = $_FILES['programa_academico_reabierto']['name']['dictamenDgp'];
                    $programaAcademicoReabierto->setDictamenDgp($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/dictamenDgp/", $file_name);
                }

                if (isset($reabierto[0])) {
                    $programaAcademicoReabiertoRepository->edit($programaAcademicoReabierto, true);
                } else {
                    $programaAcademicoReabiertoRepository->add($programaAcademicoReabierto, true);
                }


                $universidadesAsignadas = $programaAcademicoReabiertoInstitucionRepository->findBy(['programaAcademicoReabierto' => $programaAcademicoReabierto->getId()]);
                if (is_array($universidadesAsignadas))
                    foreach ($universidadesAsignadas as $value) {
                        $programaAcademicoReabiertoInstitucionRepository->remove($value, true);
                    }
                foreach ($request->request->all()['programa_academico_reabierto']['universidades'] as $value) {
                    $new = new ProgramaAcademicoReabiertoInstitucion();
                    $new->setProgramaAcademicoReabierto($programaAcademicoReabierto);
                    $new->setInstitucion($institucionRepository->find($value));
                    $programaAcademicoReabiertoInstitucionRepository->add($new, true);
                }

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
            }
            $institucionesAsignadas = [];
            if (isset($reabierto[0])) {
                $universidadesAsignadas = $programaAcademicoReabiertoInstitucionRepository->findBy(['programaAcademicoReabierto' => $programaAcademicoReabierto->getId()]);
                if (is_array($universidadesAsignadas)) {
                    foreach ($universidadesAsignadas as $value) {
                        $institucionesAsignadas[] = $value->getInstitucion()->getId();
                    }
                }
            }
            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/reabrir.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'programaAcademicoReabierto' => $programaAcademicoReabierto,
                'instituciones' => $institucionRepository->findBy([], ['nombre' => 'asc']),
                'institucionesAsignadas' => json_encode($institucionesAsignadas)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/desactivar", name="app_solicitud_programa_academico_aprobado_desactivar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaAcademicoRepository $estadoProgramaRepository
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function desactivar(Request $request, Utils $utils, EstadoProgramaAcademicoRepository $estadoProgramaRepository, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, ProgramaAcademicoDesactivadoRepository $programaAcademicoDesactivadoRepository)
    {
        try {
            $desactivar = $programaAcademicoDesactivadoRepository->findBy(['solicitudProgramaAcademico' => $solicitudPrograma->getId()]);

            $choices = [
                'resolucion' => !isset($desactivar[0]) > 0 ? 'registrar' : 'modificar',
                'dictamenAprobacion' => !isset($desactivar[0]) > 0 ? 'registrar' : 'modificar',
                'solicitudCentroRector' => !isset($desactivar[0]) > 0 ? 'registrar' : 'modificar',
            ];
            $programaAcademicoDesactivado = isset($desactivar[0]) ? $desactivar[0] : new ProgramaAcademicoDesactivado();

            $form = $this->createForm(ProgramaAcademicoDesactivadoType::class, $programaAcademicoDesactivado, $choices);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $programaAcademicoDesactivado->setFechaEliminacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['programa_academico_desactivado']['fechaEliminacion']));
                $programaAcademicoDesactivado->setSolicitudProgramaAcademico($solicitudPrograma);
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaRepository->find(7));
                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudPrograma->getId(), 7);
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                if (!empty($_FILES['programa_academico_desactivado']['name']['resolucion'])) {
                    if ($programaAcademicoDesactivado->getResolucion() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/resolucion/' . $programaAcademicoDesactivado->getResolucion())) {
                            unlink('uploads/pregrado/programas_aprobados/resolucion/' . $programaAcademicoDesactivado->getResolucion());
                        }
                    }

                    $file = $form['resolucion']->getData();
                    $file_name = $_FILES['programa_academico_desactivado']['name']['resolucion'];
                    $programaAcademicoDesactivado->setResolucion($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/resolucion/", $file_name);
                }
                if (!empty($_FILES['programa_academico_desactivado']['name']['dictamenAprobacion'])) {
                    if ($programaAcademicoDesactivado->getDictamenAprobacion() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/dictamenAprobacion/' . $programaAcademicoDesactivado->getDictamenAprobacion())) {
                            unlink('uploads/pregrado/programas_aprobados/dictamenAprobacion/' . $programaAcademicoDesactivado->getDictamenAprobacion());
                        }
                    }

                    $file = $form['dictamenAprobacion']->getData();
                    $file_name = $_FILES['programa_academico_desactivado']['name']['dictamenAprobacion'];
                    $programaAcademicoDesactivado->setDictamenAprobacion($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/dictamenAprobacion/", $file_name);
                }
                if (!empty($_FILES['programa_academico_desactivado']['name']['solicitudCentroRector'])) {
                    if ($programaAcademicoDesactivado->getSolicitudCentroRector() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/solicitudCentroRector/' . $programaAcademicoDesactivado->getSolicitudCentroRector())) {
                            unlink('uploads/pregrado/programas_aprobados/solicitudCentroRector/' . $programaAcademicoDesactivado->getSolicitudCentroRector());
                        }
                    }

                    $file = $form['solicitudCentroRector']->getData();
                    $file_name = $_FILES['programa_academico_desactivado']['name']['solicitudCentroRector'];
                    $programaAcademicoDesactivado->setSolicitudCentroRector($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/solicitudCentroRector/", $file_name);
                }

                if (isset($desactivar[0])) {
                    $programaAcademicoDesactivadoRepository->edit($programaAcademicoDesactivado, true);
                } else {
                    $programaAcademicoDesactivadoRepository->add($programaAcademicoDesactivado, true);
                }


                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/desactivar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'programaAcademicoDesactivado' => $programaAcademicoDesactivado,
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
