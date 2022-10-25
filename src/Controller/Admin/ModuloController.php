<?php

namespace App\Controller\Admin;

use App\Entity\Security\Modulo;
use App\Entity\Security\User;
use App\Form\Admin\ModuloType;
use App\Repository\Security\ModuloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/administracion/modulo")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MODULE")
 */
class ModuloController extends AbstractController
{

    /**
     * @Route("/", name="app_modulo_index", methods={"GET"})
     * @param ModuloRepository $moduloRepository
     * @return Response
     */
    public function index(ModuloRepository $moduloRepository)
    {
        try {
            return $this->render('modules/admin/modulo/index.html.twig', [
                'registros' => $moduloRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_modulo_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ModuloRepository $moduloRepository
     * @return Response
     */
    public function registrar(Request $request, ModuloRepository $moduloRepository)
    {
        try {
            $catDocenteEntity = new Modulo();
            $form = $this->createForm(ModuloType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $moduloRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/modulo/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modulo_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_modulo_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Modulo $modulo
     * @param ModuloRepository $moduloRepository
     * @return Response
     */
    public function modificar(Request $request, Modulo $modulo, ModuloRepository $moduloRepository)
    {
        try {
            $form = $this->createForm(ModuloType::class, $modulo, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $moduloRepository->edit($modulo);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/modulo/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modulo_modificar', ['id' => $modulo], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_modulo_eliminar", methods={"GET"})
     * @param Request $request
     * @param Modulo $modulo
     * @param ModuloRepository $moduloRepository
     * @return Response
     */
    public function eliminar(Request $request, Modulo $modulo, ModuloRepository $moduloRepository)
    {
        try {
            if ($moduloRepository->find($modulo) instanceof Modulo) {
                $moduloRepository->remove($modulo, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_modulo_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Modulo $modulo
     * @return Response
     */
    public function detail(Request $request, Modulo $modulo)
    {
        return $this->render('modules/admin/modulo/detail.html.twig', [
            'item' => $modulo,
        ]);
    }
}
