<?php

namespace App\Controller\Personal;

use App\Entity\Personal\NivelEscolar;
use App\Entity\Security\User;
use App\Form\Personal\NivelEscolarType;
use App\Repository\Personal\NivelEscolarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/nivel_escolar")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_NIVESC")
 */
class NivelEscolarController extends AbstractController
{

    /**
     * @Route("/", name="app_nivel_escolar_index", methods={"GET"})
     * @param NivelEscolarRepository $nivelEscolarRepository
     * @return Response
     */
    public function index(NivelEscolarRepository $nivelEscolarRepository)
    {

        return $this->render('modules/personal/nivel_escolar/index.html.twig', [
            'registros' => $nivelEscolarRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);

    }

    /**
     * @Route("/registrar", name="app_nivel_escolar_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param NivelEscolarRepository $nivelEscolarRepository
     * @return Response
     */
    public function registrar(Request $request, NivelEscolarRepository $nivelEscolarRepository)
    {
        try {
            $catDocenteEntity = new NivelEscolar();
            $form = $this->createForm(NivelEscolarType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $nivelEscolarRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_nivel_escolar_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/nivel_escolar/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nivel_escolar_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_nivel_escolar_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $nivelEscolar
     * @param NivelEscolarRepository $nivelEscolarRepository
     * @return Response
     */
    public function modificar(Request $request, NivelEscolar $nivelEscolar, NivelEscolarRepository $nivelEscolarRepository)
    {
        try {
            $form = $this->createForm(NivelEscolarType::class, $nivelEscolar, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $nivelEscolarRepository->edit($nivelEscolar);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_nivel_escolar_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/nivel_escolar/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nivel_escolar_modificar', ['id' => $nivelEscolar], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_nivel_escolar_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $nivelEscolar
     * @param NivelEscolarRepository $nivelEscolarRepository
     * @return Response
     */
    public function detail(Request $request, NivelEscolar $nivelEscolar)
    {
        return $this->render('modules/personal/nivel_escolar/detail.html.twig', [
            'item' => $nivelEscolar,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_nivel_escolar_eliminar", methods={"GET"})
     * @param Request $request
     * @param NivelEscolar $nivelEscolar
     * @param NivelEscolarRepository $nivelEscolarRepository
     * @return Response
     */
    public function eliminar(Request $request, NivelEscolar $nivelEscolar, NivelEscolarRepository $nivelEscolarRepository)
    {
        try {
            if ($nivelEscolarRepository->find($nivelEscolar) instanceof NivelEscolar) {
                $nivelEscolarRepository->remove($nivelEscolar, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_nivel_escolar_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_nivel_escolar_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nivel_escolar_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
