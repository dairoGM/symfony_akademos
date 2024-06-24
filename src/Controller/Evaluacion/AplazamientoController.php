<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\AplazamientoSolicitud;
use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Evaluacion\Solicitud;
use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Evaluacion\AprobarSolicitudType;
use App\Form\Evaluacion\RechazarSolicitudType;
use App\Form\Evaluacion\SolicitudType;
use App\Repository\Evaluacion\AplazamientoSolicitudRepository;
use App\Repository\Evaluacion\ComisionRepository;
use App\Repository\Evaluacion\EstadoAplazamientoRepository;
use App\Repository\Evaluacion\EstadoSolicitudRepository;
use App\Repository\Evaluacion\SolicitudRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Repository\Security\UserRepository;
use App\Repository\Tramite\InstitucionExtranjeraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/aplazamiento")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_APLAZAMIENTO")
 */
class AplazamientoController extends AbstractController
{

    /**
     * @Route("/", name="app_aplazamiento_index", methods={"GET"})
     * @param AplazamientoSolicitudRepository $aplazamientoSolicitudRepository
     * @return Response
     */
    public function index(AplazamientoSolicitudRepository $aplazamientoSolicitudRepository)
    {
        $registros = $aplazamientoSolicitudRepository->findBy([], ['id' => 'desc']);
        $respose = [];
        if (is_array($registros)) {
            foreach ($registros as $value) {
                $class = null;
                if ($value->getEstadoAplazamiento()->getId() == 1) {
                    $class = 'ms-status bg-yellow';
                }
                if ($value->getEstadoAplazamiento()->getId() == 2) {
                    $class = 'ms-status bg-green';
                }
                if ($value->getEstadoAplazamiento()->getId() == 3) {
                    $class = 'ms-status bg-red';
                }
                $value->aplazamientoClass = $class;
                $respose[] = $value;
            }
        }
        return $this->render('modules/evaluacion/aplazamiento/index.html.twig', [
            'registros' => $respose
        ]);
    }

    /**
     * @Route("/{id}/aprobar", name="app_aplazamiento_aprobar", methods={"GET"})
     * @param Request $request
     * @param AplazamientoSolicitud $aplazamientoSolicitud
     * @param AplazamientoSolicitudRepository $aplazamientoSolicitudRepository
     * @return Response
     */
    public function aprobar(Request $request, AplazamientoSolicitud $aplazamientoSolicitud, SolicitudRepository $solicitudRepository, AplazamientoSolicitudRepository $aplazamientoSolicitudRepository, EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        try {
            $aplazamientoSolicitud->setEstadoAplazamiento($estadoAplazamientoRepository->find(2));
            $aplazamientoSolicitudRepository->edit($aplazamientoSolicitud, true);
            $solicitud = $aplazamientoSolicitud->getSolicitud();
            $solicitud->setFechaAprobada($aplazamientoSolicitud->getFechaPropuestaAplazamiento());
            $solicitudRepository->edit($solicitud, true);

            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/rechazar", name="app_aplazamiento_rechazar", methods={"GET"})
     * @param Request $request
     * @param AplazamientoSolicitud $aplazamientoSolicitud
     * @param AplazamientoSolicitudRepository $aplazamientoSolicitudRepository
     * @return Response
     */
    public function rechazar(Request $request, AplazamientoSolicitud $aplazamientoSolicitud, AplazamientoSolicitudRepository $aplazamientoSolicitudRepository, EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        try {
            $aplazamientoSolicitud->setEstadoAplazamiento($estadoAplazamientoRepository->find(3));
            $aplazamientoSolicitudRepository->edit($aplazamientoSolicitud, true);
            $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);

            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/eliminar", name="app_aplazamiento_eliminar", methods={"GET"})
     * @param Request $request
     * @param AplazamientoSolicitud $aplazamientoSolicitud
     * @param AplazamientoSolicitudRepository $aplazamientoSolicitudRepository
     * @return Response
     */
    public function eliminar(Request $request, AplazamientoSolicitud $aplazamientoSolicitud, AplazamientoSolicitudRepository $aplazamientoSolicitudRepository)
    {
        try {

            $aplazamientoSolicitudRepository->remove($aplazamientoSolicitud, true);
            $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);

            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
