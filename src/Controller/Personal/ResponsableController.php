<?php

namespace App\Controller\Personal;

use App\Entity\Personal\Persona;
use App\Entity\Personal\Responsable;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Services\Utils;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/responsable")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_RESPONS")
 */
class ResponsableController extends AbstractController
{

    /**
     * @Route("/", name="app_responsable_index", methods={"GET"})
     * @param Request $request
     * @param ResponsableRepository $responsableRepository
     * @param Utils $utils
     * @return Response
     */
    public function index(Request $request, ResponsableRepository $responsableRepository, Utils $utils)
    {
        try {
            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
            $registros = $responsableRepository->geResponsablesDadoArrayEstructuras($estructurasNegocio);

            return $this->render('modules/personal/responsable/index.html.twig', [
                'registros' => $registros,
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_responsable_eliminar", methods={"GET"})
     * @param Request $request
     * @param Responsable $responsable
     * @param ResponsableRepository $responsableRepository
     * @return Response
     */
    public function eliminar(Request $request, Responsable $responsable, ResponsableRepository $responsableRepository)
    {
        try {
            if ($responsableRepository->find($responsable) instanceof Responsable) {
                $responsable->setActivo(false);
                $responsableRepository->edit($responsable, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/exportar_pdf", name="app_responsable_exportar_pdf", methods={"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function exportarPdf(Request $request, \App\Services\HandlerFop $handFop, ResponsableRepository $responsableRepository, Utils $utils)
    {
        $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
        $registros = $responsableRepository->geResponsablesDadoArrayEstructuras($estructurasNegocio);

        $export = $responsableRepository->getExportarListado($estructurasNegocio);
        $export = \App\Services\DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new \App\Export\Personal\ExportListResponsableToPdf($export));
    }
}
