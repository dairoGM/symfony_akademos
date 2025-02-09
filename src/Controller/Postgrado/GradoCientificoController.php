<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Postgrado\SolicitudProgramaComision;
use App\Entity\Security\User;
use App\Form\Postgrado\AprobarProgramaType;
use App\Form\Postgrado\CambioEstadoProgramaType;
use App\Form\Postgrado\ComisionProgramaType;
use App\Form\Postgrado\NoAprobarProgramaType;
use App\Form\Postgrado\SolicitudProgramaType;
use App\Repository\Postgrado\ComisionRepository;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\SolicitudProgramaComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Services\TraceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/programas_aprobados")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_GRADO_CIENTIFICO")
 */
class GradoCientificoController extends AbstractController
{

    /**
     * @Route("/grados_cientificos", name="app_posgrado_grados_cientificos_index", methods={"GET"})
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function indexGradoCientifico(SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/postgrado/programas_aprobados/index.html.twig', [
            'registros' => $solicitudProgramaRepository->findBy(['tipoPrograma' => 6, 'estadoPrograma' => 7], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

}
