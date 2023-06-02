<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\CategoriaResponsabilidad;
use App\Entity\Security\User;
use App\Form\Estructura\CategoriaResponsabilidadType;
use App\Repository\Estructura\CategoriaResponsabilidadRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/categoria_responsabilidad")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATRESP")
 */
class CategoriaResponsabilidadController extends AbstractController
{

    /**
     * @Route("/", name="app_categoria_responsabilidad_index", methods={"GET"})
     * @param CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository
     * @return Response
     */
    public function index(CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository)
    {
        try {
            return $this->render('modules/estructura/categoria_responsabilidad/index.html.twig', [
                'registros' => $categoriaResponsabilidadRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_categoria_responsabilidad_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository
     * @return Response
     */
    public function registrar(Request $request, CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository, Utils $utils)
    {
        try {
            $catResponsabilidadEntity = new CategoriaResponsabilidad();
            $form = $this->createForm(CategoriaResponsabilidadType::class, $catResponsabilidadEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaResponsabilidadRepository->add($catResponsabilidadEntity, true);

                $utils->actualizarCategoriaResponsabilidadDri($catResponsabilidadEntity);

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/categoria_responsabilidad/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_responsabilidad_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_categoria_responsabilidad_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param CategoriaResponsabilidad $categoriaResponsabilidad
     * @param CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository
     * @return Response
     */
    public function modificar(Request $request, CategoriaResponsabilidad $categoriaResponsabilidad, CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository, Utils $utils)
    {
        try {
            $form = $this->createForm(CategoriaResponsabilidadType::class, $categoriaResponsabilidad, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaResponsabilidadRepository->edit($categoriaResponsabilidad);

                $utils->actualizarCategoriaResponsabilidadDri($categoriaResponsabilidad);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/categoria_responsabilidad/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_responsabilidad_modificar', ['id' => $categoriaResponsabilidad], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_categoria_responsabilidad_eliminar", methods={"GET"})
     * @param Request $request
     * @param CategoriaResponsabilidad $categoriaResponsabilidad
     * @param CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository
     * @return Response
     */
    public function eliminar(Request $request, CategoriaResponsabilidad $categoriaResponsabilidad, CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository, Utils $utils)
    {
        try {
            if ($categoriaResponsabilidadRepository->find($categoriaResponsabilidad) instanceof CategoriaResponsabilidad) {
                $categoriaResponsabilidadRepository->remove($categoriaResponsabilidad, true);
                $utils->actualizarCategoriaResponsabilidadDri($categoriaResponsabilidad, true);

                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_categoria_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_categoria_responsabilidad_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaResponsabilidad
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function detail(Request $request, CategoriaResponsabilidad $categoriaResponsabilidad)
    {
        return $this->render('modules/estructura/categoria_responsabilidad/detail.html.twig', [
            'item' => $categoriaResponsabilidad,
        ]);
    }
}
