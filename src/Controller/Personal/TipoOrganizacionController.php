<?php

namespace App\Controller\Personal;

use App\Entity\Personal\TipoOrganizacion;
use App\Entity\Security\User;
use App\Form\Personal\TipoOrganizacionType;
use App\Repository\Personal\TipoOrganizacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/tipo_organizacion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_TYPORG")
 */
class TipoOrganizacionController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_organizacion_index", methods={"GET"})
     * @param TipoOrganizacionRepository $tipoOrganizacionRepository
     * @return Response
     */
    public function index(TipoOrganizacionRepository $tipoOrganizacionRepository)
    {
        try {
            return $this->render('modules/personal/tipo_organizacion/index.html.twig', [
                'registros' => $tipoOrganizacionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organizacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_tipo_organizacion_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoOrganizacionRepository $tipoOrganizacionRepository
     * @return Response
     */
    public function registrar(Request $request, TipoOrganizacionRepository $tipoOrganizacionRepository)
    {
        try {
            $catDocenteEntity = new TipoOrganizacion();
            $form = $this->createForm(TipoOrganizacionType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoOrganizacionRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_organizacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/tipo_organizacion/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organizacion_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_organizacion_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoOrganizacion
     * @param TipoOrganizacionRepository $tipoOrganizacionRepository
     * @return Response
     */
    public function modificar(Request $request, TipoOrganizacion $tipoOrganizacion, TipoOrganizacionRepository $tipoOrganizacionRepository)
    {
        try {
            $form = $this->createForm(TipoOrganizacionType::class, $tipoOrganizacion, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoOrganizacionRepository->edit($tipoOrganizacion);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_organizacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/tipo_organizacion/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organizacion_modificar', ['id' => $tipoOrganizacion], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_organizacion_eliminar", methods={"GET"})
     * @param Request $request
     * @param TipoOrganizacion $tipoOrganizacion
     * @param TipoOrganizacionRepository $tipoOrganizacionRepository
     * @return Response
     */
    public function eliminar(Request $request, TipoOrganizacion $tipoOrganizacion, TipoOrganizacionRepository $tipoOrganizacionRepository)
    {
        try {
            if ($tipoOrganizacionRepository->find($tipoOrganizacion) instanceof TipoOrganizacion) {
                $tipoOrganizacionRepository->remove($tipoOrganizacion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_organizacion_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_organizacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organizacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_tipo_organizacion_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoOrganizacion $tipoOrganizacion
     * @return Response
     */
    public function detail(Request $request, TipoOrganizacion $tipoOrganizacion)
    {
        return $this->render('modules/personal/tipo_organizacion/detail.html.twig', [
            'item' => $tipoOrganizacion,
        ]);
    }
}
