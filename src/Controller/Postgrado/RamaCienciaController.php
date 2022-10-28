<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\RamaCiencia;
use App\Entity\Security\User;
use App\Form\Postgrado\RamaCienciaType;
use App\Repository\Postgrado\RamaCienciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/rama_ciencia")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class RamaCienciaController extends AbstractController
{

    /**
     * @Route("/", name="app_rama_ciencia_index", methods={"GET"})
     * @param RamaCienciaRepository $ramaCienciaRepository
     * @return Response
     */
    public function index(RamaCienciaRepository $ramaCienciaRepository)
    {
        return $this->render('modules/postgrado/rama_ciencia/index.html.twig', [
            'registros' => $ramaCienciaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_rama_ciencia_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param RamaCienciaRepository $ramaCienciaRepository
     * @return Response
     */
    public function registrar(Request $request, RamaCienciaRepository $ramaCienciaRepository)
    {
        try {
            $catDocenteEntity = new RamaCiencia();
            $form = $this->createForm(RamaCienciaType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $ramaCienciaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_rama_ciencia_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/rama_ciencia/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rama_ciencia_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_rama_ciencia_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $ramaCiencia
     * @param RamaCienciaRepository $ramaCienciaRepository
     * @return Response
     */
    public function modificar(Request $request, RamaCiencia $ramaCiencia, RamaCienciaRepository $ramaCienciaRepository)
    {
        try {
            $form = $this->createForm(RamaCienciaType::class, $ramaCiencia, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ramaCienciaRepository->edit($ramaCiencia);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rama_ciencia_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/rama_ciencia/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rama_ciencia_modificar', ['id' => $ramaCiencia], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_rama_ciencia_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $ramaCiencia
     * @param RamaCienciaRepository $ramaCienciaRepository
     * @return Response
     */
    public function detail(Request $request, RamaCiencia $ramaCiencia)
    {
        return $this->render('modules/postgrado/rama_ciencia/detail.html.twig', [
            'item' => $ramaCiencia,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_rama_ciencia_eliminar", methods={"GET"})
     * @param Request $request
     * @param RamaCiencia $ramaCiencia
     * @param RamaCienciaRepository $ramaCienciaRepository
     * @return Response
     */
    public function eliminar(Request $request, RamaCiencia $ramaCiencia, RamaCienciaRepository $ramaCienciaRepository)
    {
        try {
            if ($ramaCienciaRepository->find($ramaCiencia) instanceof RamaCiencia) {
                $ramaCienciaRepository->remove($ramaCiencia, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rama_ciencia_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rama_ciencia_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rama_ciencia_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
