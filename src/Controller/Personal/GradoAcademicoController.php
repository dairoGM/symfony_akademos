<?php

namespace App\Controller\Personal;

use App\Entity\Personal\GradoAcademico;
use App\Entity\Security\User;
use App\Form\Personal\GradoAcademicoType;
use App\Repository\Personal\GradoAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/grado_academico")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_GRADAKAD")
 */
class GradoAcademicoController extends AbstractController
{

    /**
     * @Route("/", name="app_grado_academico_index", methods={"GET"})
     * @param GradoAcademicoRepository $gradoAcademicoRepository
     * @return Response
     */
    public function index(GradoAcademicoRepository $gradoAcademicoRepository)
    {

        return $this->render('modules/personal/grado_academico/index.html.twig', [
            'registros' => $gradoAcademicoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);

    }

    /**
     * @Route("/registrar", name="app_grado_academico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param GradoAcademicoRepository $gradoAcademicoRepository
     * @return Response
     */
    public function registrar(Request $request, GradoAcademicoRepository $gradoAcademicoRepository)
    {
        try {
            $catDocenteEntity = new GradoAcademico();
            $form = $this->createForm(GradoAcademicoType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $gradoAcademicoRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_grado_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/grado_academico/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_grado_academico_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_grado_academico_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $gradoAcademico
     * @param GradoAcademicoRepository $gradoAcademicoRepository
     * @return Response
     */
    public function modificar(Request $request, GradoAcademico $gradoAcademico, GradoAcademicoRepository $gradoAcademicoRepository)
    {
        try {
            $form = $this->createForm(GradoAcademicoType::class, $gradoAcademico, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $gradoAcademicoRepository->edit($gradoAcademico);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_grado_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/grado_academico/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_grado_academico_modificar', ['id' => $gradoAcademico], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_grado_academico_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $gradoAcademico
     * @param GradoAcademicoRepository $gradoAcademicoRepository
     * @return Response
     */
    public function detail(Request $request, GradoAcademico $gradoAcademico)
    {
        return $this->render('modules/personal/grado_academico/detail.html.twig', [
            'item' => $gradoAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_grado_academico_eliminar", methods={"GET"})
     * @param Request $request
     * @param GradoAcademico $gradoAcademico
     * @param GradoAcademicoRepository $gradoAcademicoRepository
     * @return Response
     */
    public function eliminar(Request $request, GradoAcademico $gradoAcademico, GradoAcademicoRepository $gradoAcademicoRepository)
    {
        try {
            if ($gradoAcademicoRepository->find($gradoAcademico) instanceof GradoAcademico) {
                $gradoAcademicoRepository->remove($gradoAcademico, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_grado_academico_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_grado_academico_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_grado_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
