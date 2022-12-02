<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\TipoProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Pregrado\TipoProgramaAcademicoType;
use App\Repository\Pregrado\TipoProgramaAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/tipo_programa_academico")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class TipoProgramaAcademicoController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_programa_academico_index", methods={"GET"})
     * @param TipoProgramaAcademicoRepository $tipoProgramaRepository
     * @return Response
     */
    public function index(TipoProgramaAcademicoRepository $tipoProgramaRepository)
    {
        return $this->render('modules/pregrado/tipo_programa_academico/index.html.twig', [
            'registros' => $tipoProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_tipo_programa_academico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoProgramaAcademicoRepository $tipoProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, TipoProgramaAcademicoRepository $tipoProgramaRepository)
    {
        try {
            $catDocenteEntity = new TipoProgramaAcademico();
            $form = $this->createForm(TipoProgramaAcademicoType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/tipo_programa_academico/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_programa_academico_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_programa_academico_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoPrograma
     * @param TipoProgramaAcademicoRepository $tipoProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, TipoProgramaAcademico $tipoPrograma, TipoProgramaAcademicoRepository $tipoProgramaRepository)
    {
        try {
            $form = $this->createForm(TipoProgramaAcademicoType::class, $tipoPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->edit($tipoPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/tipo_programa_academico/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_programa_academico_modificar', ['id' => $tipoPrograma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_programa_academico_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoProgramaAcademico $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, TipoProgramaAcademico $tipoProgramaAcademico)
    {
        return $this->render('modules/pregrado/tipo_programa_academico/detail.html.twig', [
            'item' => $tipoProgramaAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_programa_academico_eliminar", methods={"GET"})
     * @param Request $request
     * @param TipoProgramaAcademico $tipoPrograma
     * @param TipoProgramaAcademicoRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, TipoProgramaAcademico $tipoPrograma, TipoProgramaAcademicoRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($tipoPrograma) instanceof TipoProgramaAcademico) {
                $tipoProgramaRepository->remove($tipoPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
