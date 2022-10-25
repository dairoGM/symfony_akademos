<?php

namespace App\Controller\Personal;

use App\Entity\Personal\CategoriaInvestigativa;
use App\Entity\Security\User;
use App\Form\Personal\CategoriaInvestigativaType;
use App\Repository\Personal\CategoriaInvestigativaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/categoria_investigativa")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATINVST")
 */
class CategoriaInvestigativaController extends AbstractController
{

    /**
     * @Route("/", name="app_categoria_investigativa_index", methods={"GET"})
     * @param CategoriaInvestigativaRepository $categoriaInvestigativaRepository
     * @return Response
     */
    public function index(CategoriaInvestigativaRepository $categoriaInvestigativaRepository)
    {
        try {
            return $this->render('modules/personal/categoria_investigativa/index.html.twig', [
                'registros' => $categoriaInvestigativaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_investigativa_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_categoria_investigativa_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CategoriaInvestigativaRepository $categoriaInvestigativaRepository
     * @return Response
     */
    public function registrar(Request $request, CategoriaInvestigativaRepository $categoriaInvestigativaRepository)
    {
        try {
            $catInvestigativaEntity = new CategoriaInvestigativa();
            $form = $this->createForm(CategoriaInvestigativaType::class, $catInvestigativaEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaInvestigativaRepository->add($catInvestigativaEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_investigativa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/categoria_investigativa/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_investigativa_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_categoria_investigativa_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaInvestigativa
     * @param CategoriaInvestigativaRepository $categoriaInvestigativaRepository
     * @return Response
     */
    public function modificar(Request $request, CategoriaInvestigativa $categoriaInvestigativa, CategoriaInvestigativaRepository $categoriaInvestigativaRepository)
    {
        try {
            $form = $this->createForm(CategoriaInvestigativaType::class, $categoriaInvestigativa, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaInvestigativaRepository->edit($categoriaInvestigativa);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_investigativa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/categoria_investigativa/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_investigativa_modificar', ['id' => $categoriaInvestigativa], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_categoria_investigativa_eliminar", methods={"GET"})
     * @param Request $request
     * @param CategoriaInvestigativa $categoriaInvestigativa
     * @param CategoriaInvestigativaRepository $categoriaInvestigativaRepository
     * @return Response
     */
    public function eliminar(Request $request, CategoriaInvestigativa $categoriaInvestigativa, CategoriaInvestigativaRepository $categoriaInvestigativaRepository)
    {
        try {
            if ($categoriaInvestigativaRepository->find($categoriaInvestigativa) instanceof CategoriaInvestigativa) {
                $categoriaInvestigativaRepository->remove($categoriaInvestigativa, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_categoria_investigativa_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_categoria_investigativa_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_categoria_investigativa_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_categoria_investigativa_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaInvestigativa
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function detail(Request $request, CategoriaInvestigativa $categoriaInvestigativa)
    {
        return $this->render('modules/personal/categoria_investigativa/detail.html.twig', [
            'item' => $categoriaInvestigativa,
        ]);
    }
}
