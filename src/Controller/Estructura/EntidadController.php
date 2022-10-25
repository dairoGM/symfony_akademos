<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Entidad;
use App\Form\Estructura\EntidadType;
use App\Repository\Estructura\EntidadRepository;
use App\Repository\Estructura\MunicipioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/entidad")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ENTITY")
 */
class EntidadController extends AbstractController
{

    /**
     * @Route("/", name="app_entidad_index", methods={"GET"})
     * @param EntidadRepository $entidadRepository
     * @return Response
     */
    public function index(EntidadRepository $entidadRepository)
    {
        try {
            return $this->render('modules/estructura/entidad/index.html.twig', [
                'registros' => $entidadRepository->findBy([], ['id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_entidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_entidad_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EntidadRepository $entidadRepository
     * @param MunicipioRepository $municipioRepository
     * @return Response
     */
    public function registrar(Request $request, EntidadRepository $entidadRepository, MunicipioRepository $municipioRepository)
    {
        $catEntidadEntity = new Entidad();
        $form = $this->createForm(EntidadType::class, $catEntidadEntity, ['data_choices' => -1]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $catEntidadEntity->setMunicipio($municipioRepository->find($_POST['entidad']['municipio']));
            $entidadRepository->add($catEntidadEntity, true);
            $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
            return $this->redirectToRoute('app_entidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modules/estructura/entidad/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/{id}/modificar", name="app_entidad_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Entidad $entidad
     * @param EntidadRepository $entidadRepository
     * @return Response
     */
    public function modificar(Request $request, Entidad $entidad, EntidadRepository $entidadRepository, MunicipioRepository $municipioRepository)
    {
        $form = $this->createForm(EntidadType::class, $entidad, ['data_choices' => $entidad->getProvincia()->getId()]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entidad->setMunicipio($municipioRepository->find($_POST['entidad']['municipio']));
            $entidadRepository->edit($entidad);
            $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
            return $this->redirectToRoute('app_entidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modules/estructura/entidad/edit.html.twig', [
            'form' => $form->createView(),
            'entidad' => $entidad
        ]);

    }

    /**
     * @Route("/{id}/eliminar", name="app_entidad_eliminar", methods={"GET"})
     * @param Request $request
     * @param Entidad $entidad
     * @param EntidadRepository $entidadRepository
     * @return Response
     */
    public function eliminar(Request $request, Entidad $entidad, EntidadRepository $entidadRepository)
    {
        try {
            if ($entidadRepository->find($entidad) instanceof Entidad) {
                $entidadRepository->remove($entidad, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_entidad_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_entidad_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_entidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_entidad_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Entidad $entidad
     * @return Response
     */
    public function detail(Request $request, Entidad $entidad)
    {
        return $this->render('modules/estructura/entidad/detail.html.twig', [
            'item' => $entidad,
        ]);
    }

    /**
     * @Route("/exportar_pdf", name="app_entidad_exportar_pdf", methods={"GET", "POST"})
     * @param Request $request
     * @param \App\Services\HandlerFop $handFop
     * @param EntidadRepository $entidadRepository
     * @return Response
     */
    public function exportarPdf(Request $request, \App\Services\HandlerFop $handFop, EntidadRepository $entidadRepository)
    {
        $export = $entidadRepository->getExportarListado();
        $export = \App\Services\DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new \App\Export\Estructura\ExportListEntidadToPdf($export));
    }
}
