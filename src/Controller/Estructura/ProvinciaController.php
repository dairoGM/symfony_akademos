<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Provincia;
use App\Form\Admin\ProvinciaType;
use App\Repository\Estructura\ProvinciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/provincia")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PROVIN")
 */
class ProvinciaController extends AbstractController
{
    /**
     * @Route("/", name="app_provincia_index", methods={"GET"})
     * @param ProvinciaRepository $provinciaRepository
     * @return Response
     */
    public function index(ProvinciaRepository $provinciaRepository): Response
    {
        try {
            return $this->render('modules/estructura/provincia/index.html.twig', [
                'provincias' => $provinciaRepository->findBy([], ['nombre' => 'asc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_provincia_new", methods={"GET", "POST"})
     * @param Request $request
     * @param ProvinciaRepository $provinciaRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function new(Request $request, ProvinciaRepository $provinciaRepository): Response
    {
        try {
            $provincia = new Provincia();
            $form = $this->createForm(ProvinciaType::class, $provincia);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $provinciaRepository->add($provincia);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/provincia/new.html.twig', [
                'provincia' => $provincia,
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_provincia_new', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/edit", name="app_provincia_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Provincia $provincia
     * @param ProvinciaRepository $provinciaRepository
     * @return Response
     */
    public function edit(Request $request, Provincia $provincia, ProvinciaRepository $provinciaRepository): Response
    {
        try {
            $form = $this->createForm(ProvinciaType::class, $provincia);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $provinciaRepository->edit($provincia);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/provincia/edit.html.twig', [
                'provincia' => $provincia,
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_provincia_edit', ['id' => $provincia], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_provincia_delete", methods={"GET"})
     * @param Request $request
     * @param Provincia $provincia
     * @param ProvinciaRepository $provinciaRepository
     * @return Response
     */
    public function delete(Request $request, Provincia $provincia, ProvinciaRepository $provinciaRepository): Response
    {
        try {
            if ($provinciaRepository->find($provincia) instanceof Provincia) {
                $provinciaRepository->remove($provincia);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_provincia_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $provincia
     * @return Response
     */
    public function detail(Request $request, Provincia $provincia)
    {
        return $this->render('modules/estructura/provincia/detail.html.twig', [
            'item' => $provincia,
        ]);
    }
}
