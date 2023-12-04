<?php

namespace App\Controller\Personal;

use App\Entity\Personal\Carrera;
use App\Entity\Security\User;
use App\Form\Personal\CarreraType;
use App\Repository\Personal\CarreraRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/carrera")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CARRERA")
 */
class CarreraController extends AbstractController
{

    /**
     * @Route("/", name="app_carrera_index", methods={"GET"})
     * @return Response
     */
    public function index(SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
//        try {
            return $this->render('modules/personal/carrera/index.html.twig', [
                'registros' => $solicitudProgramaAcademicoRepository->getSolicitudProgramaAcademico([2,8]),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
//        }
    }

    /**
     * @Route("/registrar", name="app_carrera_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CarreraRepository $carreraRepository
     * @return Response
     */
    public function registrar(Request $request, CarreraRepository $carreraRepository)
    {
        try {
            $catDocenteEntity = new Carrera();
            $form = $this->createForm(CarreraType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $carreraRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/carrera/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_carrera_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_carrera_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $carrera
     * @param CarreraRepository $carreraRepository
     * @return Response
     */
    public function modificar(Request $request, Carrera $carrera, CarreraRepository $carreraRepository)
    {
        try {
            $form = $this->createForm(CarreraType::class, $carrera, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $carreraRepository->edit($carrera);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/carrera/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_carrera_modificar', ['id' => $carrera], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_carrera_eliminar", methods={"GET"})
     * @param Request $request
     * @param Carrera $carrera
     * @param CarreraRepository $carreraRepository
     * @return Response
     */
    public function eliminar(Request $request, Carrera $carrera, CarreraRepository $carreraRepository)
    {
        try {
            if ($carreraRepository->find($carrera) instanceof Carrera) {
                $carreraRepository->remove($carrera, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_carrera_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $carrera
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function detail(Request $request, Carrera $carrera)
    {
        return $this->render('modules/personal/carrera/detail.html.twig', [
            'item' => $carrera,
        ]);
    }
}
