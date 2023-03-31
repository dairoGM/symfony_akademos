<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\EstadoProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Pregrado\EstadoProgramaAcademicoType;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/estado_programa_academico")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class EstadoProgramaAcademicoController extends AbstractController
{

    /**
     * @Route("/", name="app_estado_programa_academico_index", methods={"GET"})
     * @param EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository
     * @return Response
     */
    public function index(EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
    {
        return $this->render('modules/pregrado/estado_programa_academico/index.html.twig', [
            'registros' => $estadoProgramaAcademicoRepository->findBy([], ['activo' => 'desc', 'id' => 'asc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_estado_programa_academico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository
     * @return Response
     */
    public function registrar(Request $request, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
    {
        try {
            $entity = new EstadoProgramaAcademico();
            $form = $this->createForm(EstadoProgramaAcademicoType::class, $entity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $estadoProgramaAcademicoRepository->add($entity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/estado_programa_academico/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_programa_academico_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_estado_programa_academico_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estadoSolicitud
     * @param EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository
     * @return Response
     */
    public function modificar(Request $request, EstadoProgramaAcademico $estadoSolicitud, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
    {
        try {
            $form = $this->createForm(EstadoProgramaAcademicoType::class, $estadoSolicitud, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $estadoProgramaAcademicoRepository->edit($estadoSolicitud);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/estado_programa_academico/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_programa_academico_modificar', ['id' => $estadoSolicitud], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_estado_programa_academico_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaAcademico $estadoSolicitud
     * @return Response
     */
    public function detail(Request $request, EstadoProgramaAcademico $estadoSolicitudAcademico)
    {
        return $this->render('modules/pregrado/estado_programa_academico/detail.html.twig', [
            'item' => $estadoSolicitudAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_estado_programa_academico_eliminar", methods={"GET"})
     * @param Request $request
     * @param EstadoProgramaAcademico $estadoSolicitud
     * @param EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository
     * @return Response
     */
    public function eliminar(Request $request, EstadoProgramaAcademico $estadoSolicitud, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
    {
        try {
            if ($estadoProgramaAcademicoRepository->find($estadoSolicitud) instanceof EstadoProgramaAcademico) {
                $estadoProgramaAcademicoRepository->remove($estadoSolicitud, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_estado_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_estado_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estado_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
