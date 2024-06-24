<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\EstadoAplazamiento;
use App\Entity\Security\User;
use App\Form\Evaluacion\EstadoAplazamientoType;
use App\Repository\Evaluacion\EstadoAplazamientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/evaluacion/estado_aplazamiento")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTADO_APLAZAMIENTO")
 */
class EstadoAplazamientoController extends AbstractController
{

    /**
     * @Route("/", name="app_estado_aplazamiento_index", methods={"GET"})
     * @param EstadoAplazamientoRepository $estadoAplazamientoRepository
     * @return Response
     */
    public function index(EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        return $this->render('modules/evaluacion/estado_aplazamiento/index.html.twig', [
            'registros' => $estadoAplazamientoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_estado_aplazamiento_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoAplazamientoRepository $estadoAplazamientoRepository
     * @return Response
     */
    public function registrar(Request $request, EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        try {
            $catDocenteEntity = new EstadoAplazamiento();
            $form = $this->createForm(EstadoAplazamientoType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $estadoAplazamientoRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/estado_aplazamiento/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_aplazamiento_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_estado_aplazamiento_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estadoAplazamiento
     * @param EstadoAplazamientoRepository $estadoAplazamientoRepository
     * @return Response
     */
    public function modificar(Request $request, EstadoAplazamiento $estadoAplazamiento, EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        try {
            $form = $this->createForm(EstadoAplazamientoType::class, $estadoAplazamiento, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $estadoAplazamientoRepository->edit($estadoAplazamiento);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/estado_aplazamiento/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_aplazamiento_modificar', ['id' => $estadoAplazamiento], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_estado_aplazamiento_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estadoAplazamiento
     * @param EstadoAplazamientoRepository $estadoAplazamientoRepository
     * @return Response
     */
    public function detail(Request $request, EstadoAplazamiento $estadoAplazamiento)
    {
        return $this->render('modules/evaluacion/estado_aplazamiento/detail.html.twig', [
            'item' => $estadoAplazamiento,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_estado_aplazamiento_eliminar", methods={"GET"})
     * @param Request $request
     * @param EstadoAplazamiento $estadoAplazamiento
     * @param EstadoAplazamientoRepository $estadoAplazamientoRepository
     * @return Response
     */
    public function eliminar(Request $request, EstadoAplazamiento $estadoAplazamiento, EstadoAplazamientoRepository $estadoAplazamientoRepository)
    {
        try {
            if ($estadoAplazamientoRepository->find($estadoAplazamiento) instanceof EstadoAplazamiento) {
                $estadoAplazamientoRepository->remove($estadoAplazamiento, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_estado_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_aplazamiento_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
