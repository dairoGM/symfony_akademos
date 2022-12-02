<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\Documento;
use App\Entity\Security\User;
use App\Form\Pregrado\DocumentoType;
use App\Repository\Pregrado\DocumentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/documento")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class DocumentoController extends AbstractController
{

    /**
     * @Route("/", name="app_documento_index", methods={"GET"})
     * @param DocumentoRepository $tipoProgramaRepository
     * @return Response
     */
    public function index(DocumentoRepository $tipoProgramaRepository)
    {
        return $this->render('modules/pregrado/documento/index.html.twig', [
            'registros' => $tipoProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_documento_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param DocumentoRepository $tipoProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, DocumentoRepository $tipoProgramaRepository)
    {
        try {
            $catDocenteEntity = new Documento();
            $form = $this->createForm(DocumentoType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/documento/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_documento_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoPrograma
     * @param DocumentoRepository $tipoProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, Documento $tipoPrograma, DocumentoRepository $tipoProgramaRepository)
    {
        try {
            $form = $this->createForm(DocumentoType::class, $tipoPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->edit($tipoPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/documento/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_modificar', ['id' => $tipoPrograma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_documento_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Documento $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, Documento $tipoProgramaAcademico)
    {
        return $this->render('modules/pregrado/documento/detail.html.twig', [
            'item' => $tipoProgramaAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_documento_eliminar", methods={"GET"})
     * @param Request $request
     * @param Documento $tipoPrograma
     * @param DocumentoRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, Documento $tipoPrograma, DocumentoRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($tipoPrograma) instanceof Documento) {
                $tipoProgramaRepository->remove($tipoPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_documento_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
