<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\CursoAcademico;
use App\Entity\Security\User;
use App\Form\Pregrado\CursoAcademicoType;
use App\Repository\Pregrado\CursoAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/curso_academico")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class CursoAcademicoController extends AbstractController
{

    /**
     * @Route("/", name="app_curso_academico_index", methods={"GET"})
     * @param CursoAcademicoRepository $cursoAcademicoRepository
     * @return Response
     */
    public function index(CursoAcademicoRepository $cursoAcademicoRepository)
    {
        return $this->render('modules/pregrado/curso_academico/index.html.twig', [
            'registros' => $cursoAcademicoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_curso_academico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CursoAcademicoRepository $cursoAcademicoRepository
     * @return Response
     */
    public function registrar(Request $request, CursoAcademicoRepository $cursoAcademicoRepository)
    {
//        try {
            $entity = new CursoAcademico();
            $form = $this->createForm(CursoAcademicoType::class, $entity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $cursoAcademicoRepository->add($entity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_curso_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/curso_academico/new.html.twig', [
                'form' => $form->createView(),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_curso_academico_registrar', [], Response::HTTP_SEE_OTHER);
//        }
    }


    /**
     * @Route("/{id}/modificar", name="app_curso_academico_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $cursoAcademico
     * @param CursoAcademicoRepository $cursoAcademicoRepository
     * @return Response
     */
    public function modificar(Request $request, CursoAcademico $cursoAcademico, CursoAcademicoRepository $cursoAcademicoRepository)
    {
        try {
            $form = $this->createForm(CursoAcademicoType::class, $cursoAcademico, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $cursoAcademicoRepository->edit($cursoAcademico);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_curso_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/curso_academico/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_curso_academico_modificar', ['id' => $cursoAcademico], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_curso_academico_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param CursoAcademico $cursoAcademico
     * @return Response
     */
    public function detail(Request $request, CursoAcademico $cursoAcademicoAcademico)
    {
        return $this->render('modules/pregrado/curso_academico/detail.html.twig', [
            'item' => $cursoAcademicoAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_curso_academico_eliminar", methods={"GET"})
     * @param Request $request
     * @param CursoAcademico $cursoAcademico
     * @param CursoAcademicoRepository $cursoAcademicoRepository
     * @return Response
     */
    public function eliminar(Request $request, CursoAcademico $cursoAcademico, CursoAcademicoRepository $cursoAcademicoRepository)
    {
        try {
            if ($cursoAcademicoRepository->find($cursoAcademico) instanceof CursoAcademico) {
                $cursoAcademicoRepository->remove($cursoAcademico, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_curso_academico_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_curso_academico_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_curso_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
