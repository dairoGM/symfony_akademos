<?php

namespace App\Controller\Traza;

use App\Entity\Traza\Objeto;
use App\Entity\Security\User;
use App\Form\Traza\ObjetoType;
use App\Repository\Traza\ObjetoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/traza/objeto")
 * @IsGranted("ROLE_ADMIN", "ROLE_OBJ_TRZ")
 */
class ObjetoController extends AbstractController
{

    /**
     * @Route("/", name="app_objeto_index", methods={"GET"})
     * @param ObjetoRepository $objetoRepository
     * @return Response
     */
    public function index(ObjetoRepository $objetoRepository)
    {

        return $this->render('modules/traza/objeto/index.html.twig', [
            'registros' => $objetoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);

    }

    /**
     * @Route("/registrar", name="app_objeto_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ObjetoRepository $objetoRepository
     * @return Response
     */
    public function registrar(Request $request, ObjetoRepository $objetoRepository)
    {
        try {
            $catDocenteEntity = new Objeto();
            $form = $this->createForm(ObjetoType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $objetoRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_objeto_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/traza/objeto/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_objeto_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_objeto_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Objeto $objeto
     * @param ObjetoRepository $objetoRepository
     * @return Response
     */
    public function modificar(Request $request, Objeto $objeto, ObjetoRepository $objetoRepository)
    {
        try {
            $form = $this->createForm(ObjetoType::class, $objeto, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $objetoRepository->edit($objeto, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_objeto_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/traza/objeto/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_objeto_modificar', ['id' => $objeto], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_objeto_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $objeto
     * @param ObjetoRepository $objetoRepository
     * @return Response
     */
    public function detail(Request $request, Objeto $objeto)
    {
        return $this->render('modules/traza/objeto/detail.html.twig', [
            'item' => $objeto,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_objeto_eliminar", methods={"GET"})
     * @param Request $request
     * @param Objeto $objeto
     * @param ObjetoRepository $objetoRepository
     * @return Response
     */
    public function eliminar(Request $request, Objeto $objeto, ObjetoRepository $objetoRepository)
    {
        try {
            if ($objetoRepository->find($objeto) instanceof Objeto) {
                $objetoRepository->remove($objeto, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_objeto_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_objeto_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_objeto_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
