<?php

namespace App\Controller\Institucion;

use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Security\User;
use App\Form\Institucion\CategoriaAcreditacionType;
use App\Repository\Institucion\CategoriaAcreditacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/institucion/categoria_acreditacion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATACRED")
 */
class CategoriaAcreditacionController extends AbstractController
{

    /**
     * @Route("/", name="app_categoria_acreditacion_index", methods={"GET"})
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @return Response
     */
    public function index(CategoriaAcreditacionRepository $categoriaAcreditacionRepository)
    {
        return $this->render('modules/institucion/categoria_acreditacion/index.html.twig', [
            'registros' => $categoriaAcreditacionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_categoria_acreditacion_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @return Response
     */
    public function registrar(Request $request, CategoriaAcreditacionRepository $categoriaAcreditacionRepository)
    {
        try {
            $catDocenteEntity = new CategoriaAcreditacion();
            $form = $this->createForm(CategoriaAcreditacionType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaAcreditacionRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_acreditacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/categoria_acreditacion/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_acreditacion_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_categoria_acreditacion_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaAcreditacion
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @return Response
     */
    public function modificar(Request $request, CategoriaAcreditacion $categoriaAcreditacion, CategoriaAcreditacionRepository $categoriaAcreditacionRepository)
    {
        try {
            $form = $this->createForm(CategoriaAcreditacionType::class, $categoriaAcreditacion, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaAcreditacionRepository->edit($categoriaAcreditacion);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_acreditacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/categoria_acreditacion/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_acreditacion_modificar', ['id' => $categoriaAcreditacion], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_categoria_acreditacion_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaAcreditacion
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @return Response
     */
    public function detail(Request $request, CategoriaAcreditacion $categoriaAcreditacion)
    {
        return $this->render('modules/institucion/categoria_acreditacion/detail.html.twig', [
            'item' => $categoriaAcreditacion,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_categoria_acreditacion_eliminar", methods={"GET"})
     * @param Request $request
     * @param CategoriaAcreditacion $categoriaAcreditacion
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @return Response
     */
    public function eliminar(Request $request, CategoriaAcreditacion $categoriaAcreditacion, CategoriaAcreditacionRepository $categoriaAcreditacionRepository)
    {
        try {
            if ($categoriaAcreditacionRepository->find($categoriaAcreditacion) instanceof CategoriaAcreditacion) {
                $categoriaAcreditacionRepository->remove($categoriaAcreditacion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_acreditacion_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_categoria_acreditacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_acreditacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
