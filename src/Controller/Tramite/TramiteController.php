<?php

namespace App\Controller\Tramite;

use App\Entity\Tramite\Tramite;
use App\Entity\Security\User;
use App\Form\Tramite\TramiteType;
use App\Repository\Tramite\TramiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/tramite")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_TRAMITE")
 */
class TramiteController extends AbstractController
{

    /**
     * @Route("/", name="app_tramite_index", methods={"GET"})
     * @param tramiteRepository $tramiteRepository
     * @return Response
     */
    public function index(TramiteRepository $tramiteRepository)
    {
        return $this->render('modules/tramite/tramite/index.html.twig', [
            'registros' => $tramiteRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_tramite_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param tramiteRepository $tramiteRepository
     * @return Response
     */
    public function registrar(Request $request, TramiteRepository $tramiteRepository)
    {
        try {
            $entidad = new Tramite();
            $form = $this->createForm(TramiteType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tramiteRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tramite_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/tramite/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tramite_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tramite_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tramite
     * @param tramiteRepository $tramiteRepository
     * @return Response
     */
    public function modificar(Request $request, Tramite $tramite, TramiteRepository $tramiteRepository)
    {
//        try {
            $form = $this->createForm(TramiteType::class, $tramite, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tramiteRepository->edit($tramite);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tramite_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/tramite/edit.html.twig', [
                'form' => $form->createView(),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_tramite_modificar', ['id' => $tramite], Response::HTTP_SEE_OTHER);
//        }
    }


    /**
     * @Route("/{id}/detail", name="app_tramite_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param tramite $tramite
     * @return Response
     */
    public function detail(Request $request, Tramite $tramite)
    {
        return $this->render('modules/tramite/tramite/detail.html.twig', [
            'item' => $tramite,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tramite_eliminar", methods={"GET"})
     * @param Request $request
     * @param tramite $tramite
     * @param tramiteRepository $tramiteRepository
     * @return Response
     */
    public function eliminar(Request $request, Tramite $tramite, TramiteRepository $tramiteRepository)
    {
        try {
            if ($tramiteRepository->find($tramite) instanceof Tramite) {
                $tramiteRepository->remove($tramite, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tramite_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tramite_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tramite_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
