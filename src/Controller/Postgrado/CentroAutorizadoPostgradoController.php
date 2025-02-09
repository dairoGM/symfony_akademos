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
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Postgrado\ComisionRepository;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\SolicitudProgramaComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Services\TraceService;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/centro_autorizado")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CENTRO_AUTORIZADO")
 */
class CentroAutorizadoPostgradoController extends AbstractController
{

    /**
     * @Route("/index", name="app_postgrado_centro_autorizado_postgrado_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function indexEstructurasCAP(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        try {
            $registros = $estructuraRepository->findBy(['centroAutorizadoPosgrado' => 1], ['activo' => 'desc', 'id' => 'desc']);
            return $this->render('modules/estructura/estructura/centroAutorizadoPosgrado.html.twig', [
                'registros' => $registros
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_entidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
