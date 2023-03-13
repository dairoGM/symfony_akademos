<?php

namespace App\Controller\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionFacultades;
use App\Entity\Institucion\InstitucionRedesSociales;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use App\Entity\Security\User;
use App\Form\Institucion\InstitucionRedesSocialesType;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\NoAprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\SolicitudProgramaAcademicoType;
use App\Form\Pregrado\SolicitudProgramaInstitucionType;
use App\Form\Pregrado\SolicitudProgramaPlanEstudioType;
use App\Repository\Institucion\InstitucionFacultadesRepository;
use App\Repository\Institucion\InstitucionRedesSocialesRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoPlanEstudioRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
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
            'registros' => $solicitudProgramaRepository->findBy(['estadoProgramaAcademico' => 2], ['activo' => 'desc', 'id' => 'desc']),
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
    public function detail(Request $request, SolicitudProgramaAcademico $solicitudProgramaAcademico)
    {
        return $this->render('modules/pregrado/solicitud_programa_academico_aprobado/detail.html.twig', [
            'item' => $solicitudProgramaAcademico,
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
    public function asignarPlanEstudio(Request $request, SolicitudProgramaAcademico $solicitudProgramaAcademico, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository)
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
     * @param Request $request
     * @param SolicitudProgramaAcademicoPlanEstudio $solicitudProgramaAcademicoPlanEstudio
     * @param SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository
     * @return Response
     */
    public function eliminarPlanEstudio(Request $request, SolicitudProgramaAcademicoPlanEstudio $solicitudProgramaAcademicoPlanEstudio, SolicitudProgramaAcademicoPlanEstudioRepository $solicitudProgramaAcademicoPlanEstudioRepository)
    {
        try {
            if ($solicitudProgramaAcademicoPlanEstudio instanceof SolicitudProgramaAcademicoPlanEstudio) {
                $solicitudProgramaAcademicoPlanEstudioRepository->remove($solicitudProgramaAcademicoPlanEstudio, true);
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

}
