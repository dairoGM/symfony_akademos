<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\Proceso;
use App\Form\Informatizacion\ProcesoType;
use App\Repository\Informatizacion\ProcesoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/proceso")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MARCA")
 */
class ProcesoController extends AbstractController
{

    /**
     * @Route("/", name="app_proceso_index", methods={"GET"})
     * @param ProcesoRepository $ProcesoRepository
     * @return Response
     */
    public function index(ProcesoRepository $ProcesoRepository)
    {
        return $this->render('modules/informatizacion/proceso/index.html.twig', [
            'registros' => $ProcesoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_proceso_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ProcesoRepository $procesoRepository
     * @return Response
     */
    public function registrar(Request $request, ProcesoRepository $procesoRepository)
    {
        try {
            $entidad = new Proceso();
            $form = $this->createForm(ProcesoType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $procesoRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_proceso_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/proceso/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_proceso_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_proceso_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Proceso $proceso
     * @param ProcesoRepository $procesoRepository
     * @return Response
     */
    public function modificar(Request $request, Proceso $proceso, ProcesoRepository $procesoRepository)
    {
        try {
            $form = $this->createForm(ProcesoType::class, $proceso, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $procesoRepository->edit($proceso);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_proceso_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/proceso/edit.html.twig', [
                'form' => $form->createView(),
                'proceso' => $proceso
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_proceso_modificar', ['id' => $proceso->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_proceso_detail", methods={"GET", "POST"})
     * @param proceso $proceso
     * @return Response
     */
    public function detail(Proceso $proceso)
    {
        return $this->render('modules/informatizacion/proceso/detail.html.twig', [
            'item' => $proceso,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_proceso_eliminar", methods={"GET"})
     * @param Proceso $proceso
     * @param ProcesoRepository $procesoRepository
     * @return Response
     */
    public function eliminar(Proceso $proceso, ProcesoRepository $procesoRepository)
    {
        try {
            if ($procesoRepository->find($proceso) instanceof Proceso) {
                $procesoRepository->remove($proceso, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_proceso_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_proceso_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_proceso_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
