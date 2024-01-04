<?php

namespace App\Controller\Pregrado;

use App\Entity\NotificacionesUsuario;
use App\Entity\Pregrado\MiembrosComisionNacional;
use App\Entity\Pregrado\ProgramaAcademicoDesactivado;
use App\Entity\Pregrado\ProgramaAcademicoReabierto;
use App\Entity\Pregrado\ProgramaAcademicoReabiertoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use App\Export\Personal\ExportListPersonaToPdf;
use App\Export\Pregrado\ExportListSolicitudProgramaAcademicoToPdf;
use App\Export\Pregrado\ExportListSolicitudUniversidadesAsignadasToPdf;
use App\Form\Pregrado\AprobarModificarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\ProgramaAcademicoDesactivadoType;
use App\Form\Pregrado\ProgramaAcademicoReabiertoType;
use App\Form\Pregrado\SolicitudProgramaComisionType;
use App\Form\Pregrado\SolicitudProgramaInstitucionType;
use App\Form\Pregrado\SolicitudProgramaPlanEstudioType;
use App\Repository\Institucion\CategoriaAcreditacionRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Personal\PersonaRepository;
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
use App\Services\DoctrineHelper;
use App\Services\HandlerFop;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/solicitud_programa_academico_aprobado")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PROGAPROB")
 * @ORM\Entity
 * @ORM\Table(name="solicitud_programa_academico_aprobado_controller")
 */
class SolicitudProgramaAcademicoAprobadoController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_academico_aprobado_index", methods={"GET"})
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository)
    {
        $response = [];
        $registros = $solicitudProgramaRepository->getSolicitudProgramaAcademicoAprobado([2, 4, 5, 6, 7]);
        if (is_array($registros)) {
            foreach ($registros as $value) {
                $temp = $solicitudProgramaAcademicoPlanEstudioRepository->findBy(['solicitudProgramaAcademico' => $value->getId()]);
                if (isset($temp[0])) {
                    $value->plan_estudio = $temp[0]->getPlanEstudio()->getPlanEstudio();
                }
                $response[] = $value;
            }
        }
        return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/index.html.twig', [
            'registros' => $response,
        ]);
    }

    /**
     *
     * /**
     * @Route("/{id}/detail", name="app_solicitud_programa_academico_aprobado_detail", methods={"GET", "POST"})
     * @param ProgramaAcademicoReabiertoInstitucionRepository $programaAcademicoReabiertoInstitucionRepository
     * @param SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository
     * @param ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return Response
     */
    public function detail(ProgramaAcademicoReabiertoRepository                 $programaAcademicoReabiertoRepository,
                           ProgramaAcademicoReabiertoInstitucionRepository      $programaAcademicoReabiertoInstitucionRepository,
                           SolicitudProgramaAcademicoPlanEstudioRepository      $solicitudProgramaAcademicoPlanEstudioRepository,
                           ModificacionPlanEstudioRepository                    $modificacionPlanEstudioRepository,
                           SolicitudProgramaAcademico                           $solicitudProgramaAcademico,
                           SolicitudProgramaAcademicoInstitucionRepository      $solicitudProgramaAcademicoInstitucionRepository,
                           SolicitudProgramaAcademicoComisionNacionalRepository $solComNac,
                           SolicitudProgramaAcademicoComisionNacionalRepository $solComisionNacional,
                           MiembrosComisionNacionalRepository                   $miembrosComisionNacional)
    {

        $planEstudio = $solicitudProgramaAcademicoPlanEstudioRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]);
        $planEstudioAsociado = -1;
        if (is_array($planEstudio) && count($planEstudio) > 0) {
            $planEstudioAsociado = $planEstudio[0]->getPlanEstudio()->getId();
        }

        $programaReabierto = $programaAcademicoReabiertoRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]);

        $idComision = $solComisionNacional->getIdComisionNacional($solicitudProgramaAcademico->getId());

        $presidente = $miembrosComisionNacional->findBy(['comision' => $idComision, 'rolComision' => 1]);
        $nombrePresidente = null;
        if (isset($presidente[0])) {
            $nombrePresidente = $presidente[0]->getMiembro()->getPrimerNombre() . ' ' . $presidente[0]->getMiembro()->getSegundoNombre() . $presidente[0]->getMiembro()->getPrimerApellido() . $presidente[0]->getMiembro()->getSegundoApellido();
        }

        return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/detail.html.twig', [
            'item' => $solicitudProgramaAcademico,
            'format' => 'col2',
            'presindeteComision' => $nombrePresidente,
            'comisionAsignada' => $solComNac->getComisionNacional($solicitudProgramaAcademico->getId()),
            'universidades' => $solicitudProgramaAcademicoInstitucionRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]),
            'modificacionesPlanEstudio' => $modificacionPlanEstudioRepository->findBy(['planEstudio' => $planEstudioAsociado]),
            'universidadesIncorporadas' => isset($programaReabierto[0]) ? $programaAcademicoReabiertoInstitucionRepository->findBy(['programaAcademicoReabierto' => $programaReabierto[0]->getId()]) : []
        ]);
    }


    /**
     * @Route("/{id}/asignar_universidad", name="app_solicitud_programa_academico_aprobado_asignar_universidad", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudProgramaAcademico
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return Response
     */
    public function asignarUniversidad(Request $request, CategoriaAcreditacionRepository $categoriaAcreditacionRepository, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        try {
            $entidad = new SolicitudProgramaAcademicoInstitucion();
            $form = $this->createForm(SolicitudProgramaInstitucionType::class, $entidad);
            $form->handleRequest($request);

            $modalidades = [];
            if ($solicitudProgramaAcademico->getModalidadDiurno()) {
                $item['id'] = 'diurno';
                $item['nombre'] = 'Diurno';
                $modalidades[] = $item;
            }
            if ($solicitudProgramaAcademico->getModalidadPorEncuentro()) {
                $item['id'] = 'por_encuentro';
                $item['nombre'] = 'Por encuentro';
                $modalidades[] = $item;
            }

            if ($solicitudProgramaAcademico->getModalidadADistancia()) {
                $item['id'] = 'a_distancia';
                $item['nombre'] = 'A distancia';
                $modalidades[] = $item;
            }

            //validar tambien la modalidad
            if ($form->isSubmitted() && $form->isValid()) {
//                $campo = "modalidadDiurno";
//                if ('por_encuentro' == $request->request->all()['modalidad']) {
//                    $campo = "modalidadPorEncuentro";
//                }
//                if ('a_distancia' == $request->request->all()['modalidad']) {
//                    $campo = "modalidadADistancia";
//                }
//
                $exist = $solicitudProgramaAcademicoInstitucionRepository->findBy(
                    [
                        'solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId(),
                        'institucion' => $request->request->all()['solicitud_programa_institucion']['institucion'],
//                        "$campo" => 'true'
                    ]);
                if (empty($exist)) {
                    $entidad->setSolicitudProgramaAcademico($solicitudProgramaAcademico);

                    if (isset($request->request->all()['modalidad'])) {
//                        if ('diurno' == $request->request->all()['modalidad']) {
                        if (in_array('diurno', $request->request->all()['modalidad'])) {
                            $entidad->setModalidadDiurno(true);
                            $entidad->setDuracionCursoDiurno($entidad->getSolicitudProgramaAcademico()->getDuracionCursoDiurno());
                        }
                        if (in_array('por_encuentro', $request->request->all()['modalidad'])) {
                            $entidad->setModalidadPorEncuentro(true);
                            $entidad->setDuracionCursoPorEncuentro($entidad->getSolicitudProgramaAcademico()->getDuracionCursoPorEncuentro());
                        }
                        if (in_array('a_distancia', $request->request->all()['modalidad'])) {
                            $entidad->setModalidadADistancia(true);
                            $entidad->setDuracionCursoADistancia($entidad->getSolicitudProgramaAcademico()->getDuracionCursoADistancia());
                        }
                    }
                    $solicitudProgramaAcademicoInstitucionRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_asignar_universidad', ['id' => $solicitudProgramaAcademico->getId()], Response::HTTP_SEE_OTHER);
            }
            $registros = $solicitudProgramaAcademicoInstitucionRepository->findBy(['solicitudProgramaAcademico' => $solicitudProgramaAcademico->getId()]);
            $data = [];
            if (is_array($registros) && count($registros) > 0) {
                foreach ($registros as $value) {
                    $item['nombre'] = "(" . $value->getInstitucion()->getSiglas() . ") " . $value->getInstitucion()->getNombre();
                    $item['centro_rector'] = ($value->getInstitucion()->getId() == $solicitudProgramaAcademico->getCentroRector()->getId()) ? "Sí" : "No";
                    $item['categoria_acreditacion'] = method_exists($value->getCategoriaAcreditacion(), 'getNombre') ? $value->getCategoriaAcreditacion()->getNombre() : null;
                    $item['asignada_a'] = $solicitudProgramaAcademico->getNombre();
                    $data[] = $item;
                }
            }
            $request->getSession()->set('universidades_asignadas_' . $this->getUser()->getId(), $data);

            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/asignar_universidad.html.twig', [
                'form' => $form->createView(),
                'modalidades' => $modalidades,
                'categoriasAcreditacion' => $categoriaAcreditacionRepository->findBy([], ['nombre' => 'asc']),
                'solicitudProgramaAcademico' => $solicitudProgramaAcademico,
                'registros' => $registros
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_universidad", name="app_solicitud_programa_academico_aprobado_eliminar_universidad", methods={"GET"})
     * @param SolicitudProgramaAcademicoInstitucion $solicitudProgramaAcademicoInstitucion
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return Response
     */
    public function eliminarUniversidad(SolicitudProgramaAcademicoInstitucion $solicitudProgramaAcademicoInstitucion, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
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
     * @param SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository
     * @param SolicitudProgramaAcademicoPlanEstudio $solicitudProgramaAcademicoPlanEstudio
     * @return Response
     */
    public function eliminarPlanEstudio(Utils $utils, SolicitudProgramaAcademicoPlanEstudio $solicitudProgramaAcademicoPlanEstudio, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        try {
            if ($solicitudProgramaAcademicoPlanEstudio instanceof SolicitudProgramaAcademicoPlanEstudio) {
                $solicitudProgramaAcademicoPlanEstudioRepository->remove($solicitudProgramaAcademicoPlanEstudio, true);

                $solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(4));

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademicoPlanEstudio->getSolicitudProgramaAcademico()->getId(), 4);

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

                    $solicitudProgramaAcademico->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(5));

                    $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademico->getId(), 5);

                    $solicitudProgramaAcademicoRepository->edit($solicitudProgramaAcademico);

                    //Notificacion a los miembros de la comision nacional
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

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademicoComisionNacional->getSolicitudProgramaAcademico()->getId(), 2);

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
                'fundamentacionReapertura' => !isset($reabierto[0]) > 0 ? 'registrar' : 'modificar',
                'dictamenDgp' => !isset($reabierto[0]) > 0 ? 'registrar' : 'modificar',
            ];
            $programaAcademicoReabierto = isset($reabierto[0]) ? $reabierto[0] : new ProgramaAcademicoReabierto();

            $form = $this->createForm(ProgramaAcademicoReabiertoType::class, $programaAcademicoReabierto, $choices);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $programaAcademicoReabierto->setSolicitudProgramaAcademico($solicitudPrograma);
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaRepository->find(7));
                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudPrograma->getId(), 7);
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                if (!empty($_FILES['programa_academico_reabierto']['name']['fundamentacionReapertura'])) {
                    if ($programaAcademicoReabierto->getFundamentacionReapertura() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/fundamentacionReapertura/' . $programaAcademicoReabierto->getFundamentacionReapertura())) {
                            unlink('uploads/pregrado/programas_aprobados/fundamentacionfundamentacionReapertura/' . $programaAcademicoReabierto->getFundamentacionReapertura());
                        }
                    }

                    $file = $form['fundamentacionReapertura']->getData();
                    $file_name = $_FILES['programa_academico_reabierto']['name']['fundamentacionReapertura'];
                    $programaAcademicoReabierto->setFundamentacionReapertura($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/fundamentacionReapertura/", $file_name);
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
                $dictamenAprobacion = null;
                $programaAcademicoDesactivado->setFechaEliminacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['programa_academico_desactivado']['fechaEliminacion']));
                $programaAcademicoDesactivado->setSolicitudProgramaAcademico($solicitudPrograma);
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaRepository->find(6));
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                if (!empty($_FILES['programa_academico_desactivado']['name']['resolucionDesactivacion'])) {
                    if ($programaAcademicoDesactivado->getResolucionDesactivacion() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/resolucion_desactivacion/' . $programaAcademicoDesactivado->getResolucionDesactivacion())) {
                            unlink('uploads/pregrado/programas_aprobados/resolucion_desactivacion/' . $programaAcademicoDesactivado->getResolucionDesactivacion());
                        }
                    }

                    $file = $form['resolucionDesactivacion']->getData();
                    $file_name = $_FILES['programa_academico_desactivado']['name']['resolucionDesactivacion'];
                    $programaAcademicoDesactivado->setResolucionDesactivacion($file_name);
                    $file->move("uploads/pregrado/programas_aprobados/resolucion_desactivacion/", $file_name);
                }
                if (!empty($_FILES['programa_academico_desactivado']['name']['dictamenAprobacion'])) {
                    if ($programaAcademicoDesactivado->getDictamenAprobacion() != null) {
                        if (file_exists('uploads/pregrado/programas_aprobados/dictamenAprobacion/' . $programaAcademicoDesactivado->getDictamenAprobacion())) {
                            unlink('uploads/pregrado/programas_aprobados/dictamenAprobacion/' . $programaAcademicoDesactivado->getDictamenAprobacion());
                        }
                    }

                    $file = $form['dictamenAprobacion']->getData();
                    $dictamenAprobacion = $_FILES['programa_academico_desactivado']['name']['dictamenAprobacion'];
                    $programaAcademicoDesactivado->setDictamenAprobacion($dictamenAprobacion);
                    $file->move("uploads/pregrado/programas_aprobados/dictamenAprobacion/", $dictamenAprobacion);
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

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudPrograma->getId(), 6, $programaAcademicoDesactivado->getCursoAcademico(), $dictamenAprobacion);

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


    /**
     * @Route("/{id}/modificar", name="app_solicitud_programa_academico_aprobar_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function aprobarModificar(Request $request, SolicitudProgramaAcademico $solicitudPrograma, EntityManagerInterface $em, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        try {
            $form = $this->createForm(AprobarModificarSolicitudProgramaAcademicoType::class, $solicitudPrograma, []);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $solicitudProgramaRepository->edit($solicitudPrograma, true);

                $programaAcademicoInstitucion = $solicitudProgramaAcademicoInstitucionRepository->findBy([
                    'solicitudProgramaAcademico' => $solicitudPrograma->getId(),
                    'institucion' => $solicitudPrograma->getCentroRector()->getId()
                ]);
                $solicitudProgramaAcademicoInstitucion = null;
                if (isset($programaAcademicoInstitucion[0]) && $programaAcademicoInstitucion[0] instanceof SolicitudProgramaAcademicoInstitucion) {
                    $solicitudProgramaAcademicoInstitucion = $programaAcademicoInstitucion[0];
                    $solicitudProgramaAcademicoInstitucion->setCategoriaAcreditacion($solicitudPrograma->getCategoriaAcreditacion());
                } else {
                    $solicitudProgramaAcademicoInstitucion = new SolicitudProgramaAcademicoInstitucion();
                    $solicitudProgramaAcademicoInstitucion->setSolicitudProgramaAcademico($solicitudPrograma);
                    $solicitudProgramaAcademicoInstitucion->setInstitucion($solicitudPrograma->getCentroRector());
                }

                $solicitudProgramaAcademicoInstitucion->setCategoriaAcreditacion($solicitudPrograma->getCategoriaAcreditacion());
                $solicitudProgramaAcademicoInstitucion->setModalidadADistancia($solicitudPrograma->getModalidadADistancia());
                $solicitudProgramaAcademicoInstitucion->setModalidadPorEncuentro($solicitudPrograma->getModalidadPorEncuentro());
                $solicitudProgramaAcademicoInstitucion->setModalidadDiurno($solicitudPrograma->getModalidadDiurno());
                $solicitudProgramaAcademicoInstitucion->setDuracionCursoDiurno($solicitudPrograma->getDuracionCursoDiurno());
                $solicitudProgramaAcademicoInstitucion->setDuracionCursoADistancia($solicitudPrograma->getDuracionCursoADistancia());
                $solicitudProgramaAcademicoInstitucion->setDuracionCursoPorEncuentro($solicitudPrograma->getDuracionCursoPorEncuentro());

                $em->persist($solicitudProgramaAcademicoInstitucion);
                $em->flush();

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/modificar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_aprobado_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * Add package entity.
     *
     * @Route("/cambio_categoria_acreditacion", name="app_solicitud_programa_academico_aprobar_guardar_cambio_categoria_acreditacion", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @param SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository
     * @return JsonResponse
     */
    public function guardarConfiguracion(Request $request, EntityManagerInterface $em, CategoriaAcreditacionRepository $categoriaAcreditacionRepository, SolicitudProgramaAcademicoInstitucionRepository $solicitudProgramaAcademicoInstitucionRepository)
    {
        try {
            $allPost = $request->request->all();

            $solicitudProgramaAcademicoInstitucion = $solicitudProgramaAcademicoInstitucionRepository->find($allPost['id']);
            if ($solicitudProgramaAcademicoInstitucion instanceof SolicitudProgramaAcademicoInstitucion) {
                $categoriaAcreditacion = $categoriaAcreditacionRepository->find($allPost['categoriaAcreditacion']);
                $solicitudProgramaAcademicoInstitucion->setCategoriaAcreditacion($categoriaAcreditacion);

                $em->persist($solicitudProgramaAcademicoInstitucion);
                if ($solicitudProgramaAcademicoInstitucion->getSolicitudProgramaAcademico()->getCentroRector()->getId() == $allPost['institucion']) {
                    $solicitudProgramaAcademicoInstitucion->getSolicitudProgramaAcademico()->setCategoriaAcreditacion($categoriaAcreditacion);
                    $em->persist($solicitudProgramaAcademicoInstitucion->getSolicitudProgramaAcademico());
                }
                $em->flush();
            }

            return $this->json(['message' => 'ok', 'status' => Response::HTTP_OK]);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage(), 'status' => Response::HTTP_BAD_REQUEST]);
        }
    }

    /**
     * @Route("/exportar_pdf", name="app_solicitud_programa_academico_aprobar_universidades_asignadas_exportar_pdf", methods={"GET", "POST"})
     * @param HandlerFop $handFop
     * @return Response
     */
    public function exportarPdf(Request $request, HandlerFop $handFop)
    {
        $export = $request->getSession()->get('universidades_asignadas_' . $this->getUser()->getId());
        $export = DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new ExportListSolicitudUniversidadesAsignadasToPdf($export, ['asignada_a' => $export[0]['asignada_a'] ?? null]));

    }
}
