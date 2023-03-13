<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\NoAprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\SolicitudProgramaAcademicoType;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
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
            'registros' => $solicitudProgramaRepository->findBy(['estadoProgramaAcademico'=>2], ['activo' => 'desc', 'id' => 'desc']),
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


}
