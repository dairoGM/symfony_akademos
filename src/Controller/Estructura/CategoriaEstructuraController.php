<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Security\User;
use App\Form\Estructura\CategoriaEstructuraType;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/categoria_estructura")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATGEST")
 */
class CategoriaEstructuraController extends AbstractController
{

    /**
     * @Route("/", name="app_categoria_estructura_index", methods={"GET"})
     * @param CategoriaEstructuraRepository $categoriaEstructuraRepository
     * @return Response
     */
    public function index(CategoriaEstructuraRepository $categoriaEstructuraRepository)
    {
        try {
            return $this->render('modules/estructura/categoria_estructura/index.html.twig', [
                'registros' => $categoriaEstructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_estructura_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_categoria_estructura_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CategoriaEstructuraRepository $categoriaEstructuraRepository
     * @return Response
     */
    public function registrar(Request $request, CategoriaEstructuraRepository $categoriaEstructuraRepository, Utils $utils)
    {
        try {
            $catEstructuraEntity = new CategoriaEstructura();
            $form = $this->createForm(CategoriaEstructuraType::class, $catEstructuraEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaEstructuraRepository->add($catEstructuraEntity, true);

                $utils->actualizarCategoriaEstructuraDri($catEstructuraEntity);

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_estructura_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/categoria_estructura/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_estructura_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_categoria_estructura_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Utils $utils
     * @param CategoriaEstructura $categoriaEstructura
     * @param CategoriaEstructuraRepository $categoriaEstructuraRepository
     * @return Response
     */
    public function modificar(Request $request, Utils $utils, CategoriaEstructura $categoriaEstructura, CategoriaEstructuraRepository $categoriaEstructuraRepository)
    {
        try {
            $form = $this->createForm(CategoriaEstructuraType::class, $categoriaEstructura, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaEstructuraRepository->edit($categoriaEstructura);

                $utils->actualizarCategoriaEstructuraDri($categoriaEstructura);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_estructura_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/categoria_estructura/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_estructura_modificar', ['id' => $categoriaEstructura], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_categoria_estructura_eliminar", methods={"GET"})
     * @param CategoriaEstructuraRepository $categoriaEstructuraRepository
     * @param CategoriaEstructura $categoriaEstructura
     * @return Response
     */
    public function eliminar(Utils $utils, CategoriaEstructura $categoriaEstructura, CategoriaEstructuraRepository $categoriaEstructuraRepository)
    {
        try {
            if ($categoriaEstructuraRepository->find($categoriaEstructura) instanceof CategoriaEstructura) {
                $categoriaEstructuraRepository->remove($categoriaEstructura, true);

                $utils->actualizarCategoriaEstructuraDri($categoriaEstructura, true);

                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_estructura_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_categoria_estructura_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_estructura_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_categoria_estructura_detail", methods={"GET", "POST"})
     * @param CategoriaEstructura $categoriaEstructura
     * @return Response
     */
    public function detail(CategoriaEstructura $categoriaEstructura)
    {
        return $this->render('modules/estructura/categoria_estructura/detail.html.twig', [
            'item' => $categoriaEstructura,
        ]);
    }
}
