<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\Visibilidad;
use App\Form\Informatizacion\VisibilidadType;
use App\Repository\Informatizacion\VisibilidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/visibilidad")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_VISIBILIDAD")
 */
class VisibilidadController extends AbstractController
{

    /**
     * @Route("/", name="app_visibilidad_index", methods={"GET"})
     * @param VisibilidadRepository $VisibilidadRepository
     * @return Response
     */
    public function index(VisibilidadRepository $VisibilidadRepository)
    {
        return $this->render('modules/informatizacion/visibilidad/index.html.twig', [
            'registros' => $VisibilidadRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_visibilidad_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param VisibilidadRepository $visibilidadRepository
     * @return Response
     */
    public function registrar(Request $request, VisibilidadRepository $visibilidadRepository)
    {
        try {
            $entidad = new Visibilidad();
            $form = $this->createForm(VisibilidadType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $visibilidadRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_visibilidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/visibilidad/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_visibilidad_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_visibilidad_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Visibilidad $visibilidad
     * @param VisibilidadRepository $visibilidadRepository
     * @return Response
     */
    public function modificar(Request $request, Visibilidad $visibilidad, VisibilidadRepository $visibilidadRepository)
    {
        try {
            $form = $this->createForm(VisibilidadType::class, $visibilidad, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $visibilidadRepository->edit($visibilidad);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_visibilidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/visibilidad/edit.html.twig', [
                'form' => $form->createView(),
                'visibilidad' => $visibilidad
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_visibilidad_modificar', ['id' => $visibilidad->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_visibilidad_detail", methods={"GET", "POST"})
     * @param visibilidad $visibilidad
     * @return Response
     */
    public function detail(Visibilidad $visibilidad)
    {
        return $this->render('modules/informatizacion/visibilidad/detail.html.twig', [
            'item' => $visibilidad,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_visibilidad_eliminar", methods={"GET"})
     * @param Visibilidad $visibilidad
     * @param VisibilidadRepository $visibilidadRepository
     * @return Response
     */
    public function eliminar(Visibilidad $visibilidad, VisibilidadRepository $visibilidadRepository)
    {
        try {
            if ($visibilidadRepository->find($visibilidad) instanceof Visibilidad) {
                $visibilidadRepository->remove($visibilidad, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_visibilidad_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_visibilidad_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_visibilidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
