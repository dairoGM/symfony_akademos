<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\PublicoObjetivo;
use App\Form\Informatizacion\PublicoObjetivoType;
use App\Repository\Informatizacion\PublicoObjetivoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/publico_objetivo")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PUBLICO_OBJETIVO")
 */
class PublicoObjetivoController extends AbstractController
{

    /**
     * @Route("/", name="app_publico_objetivo_index", methods={"GET"})
     * @param PublicoObjetivoRepository $PublicoObjetivoRepository
     * @return Response
     */
    public function index(PublicoObjetivoRepository $PublicoObjetivoRepository)
    {
        return $this->render('modules/informatizacion/publicoObjetivo/index.html.twig', [
            'registros' => $PublicoObjetivoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_publico_objetivo_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param PublicoObjetivoRepository $publicoObjetivoRepository
     * @return Response
     */
    public function registrar(Request $request, PublicoObjetivoRepository $publicoObjetivoRepository)
    {
        try {
            $entidad = new PublicoObjetivo();
            $form = $this->createForm(PublicoObjetivoType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $publicoObjetivoRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_publico_objetivo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/publicoObjetivo/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_publico_objetivo_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_publico_objetivo_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PublicoObjetivo $publicoObjetivo
     * @param PublicoObjetivoRepository $publicoObjetivoRepository
     * @return Response
     */
    public function modificar(Request $request, PublicoObjetivo $publicoObjetivo, PublicoObjetivoRepository $publicoObjetivoRepository)
    {
        try {
            $form = $this->createForm(PublicoObjetivoType::class, $publicoObjetivo, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $publicoObjetivoRepository->edit($publicoObjetivo);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_publico_objetivo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/publicoObjetivo/edit.html.twig', [
                'form' => $form->createView(),
                'publicoObjetivo' => $publicoObjetivo
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_publico_objetivo_modificar', ['id' => $publicoObjetivo->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_publico_objetivo_detail", methods={"GET", "POST"})
     * @param publicoObjetivo $publicoObjetivo
     * @return Response
     */
    public function detail(PublicoObjetivo $publicoObjetivo)
    {
        return $this->render('modules/informatizacion/publicoObjetivo/detail.html.twig', [
            'item' => $publicoObjetivo,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_publico_objetivo_eliminar", methods={"GET"})
     * @param PublicoObjetivo $publicoObjetivo
     * @param PublicoObjetivoRepository $publicoObjetivoRepository
     * @return Response
     */
    public function eliminar(PublicoObjetivo $publicoObjetivo, PublicoObjetivoRepository $publicoObjetivoRepository)
    {
        try {
            if ($publicoObjetivoRepository->find($publicoObjetivo) instanceof PublicoObjetivo) {
                $publicoObjetivoRepository->remove($publicoObjetivo, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_publico_objetivo_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_publico_objetivo_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_publico_objetivo_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
