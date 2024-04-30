<?php

namespace App\Controller\Tramite;

use App\Entity\Tramite\EstadoFichaSalida;
use App\Entity\Security\User;
use App\Form\Tramite\EstadoFichaSalidaType;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/estado_ficha_salida")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CONCEPTO_SALIDA")
 */
class EstadoFichaSalidaController extends AbstractController
{

    /**
     * @Route("/", name="app_estado_ficha_salida_index", methods={"GET"})
     * @param estadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function index(EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        return $this->render('modules/tramite/estado_ficha_salida/index.html.twig', [
            'registros' => $estadoFichaSalidaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_estado_ficha_salida_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param estadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function registrar(Request $request, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            $entidad = new estadoFichaSalida();
            $form = $this->createForm(EstadoFichaSalidaType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $estadoFichaSalidaRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/estado_ficha_salida/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_ficha_salida_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_estado_ficha_salida_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estadoFichaSalida
     * @param estadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function modificar(Request $request, EstadoFichaSalida $estadoFichaSalida, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            $form = $this->createForm(EstadoFichaSalidaType::class, $estadoFichaSalida, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $estadoFichaSalidaRepository->edit($estadoFichaSalida);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/estado_ficha_salida/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_ficha_salida_modificar', ['id' => $estadoFichaSalida], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_estado_ficha_salida_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param estadoFichaSalida $estadoFichaSalida
     * @return Response
     */
    public function detail(Request $request, EstadoFichaSalida $estadoFichaSalida)
    {
        return $this->render('modules/tramite/estado_ficha_salida/detail.html.twig', [
            'item' => $estadoFichaSalida,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_estado_ficha_salida_eliminar", methods={"GET"})
     * @param Request $request
     * @param estadoFichaSalida $estadoFichaSalida
     * @param estadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function eliminar(Request $request, EstadoFichaSalida $estadoFichaSalida, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            if ($estadoFichaSalidaRepository->find($estadoFichaSalida) instanceof EstadoFichaSalida) {
                $estadoFichaSalidaRepository->remove($estadoFichaSalida, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_estado_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
