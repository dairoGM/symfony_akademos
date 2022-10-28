<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\RolComision;
use App\Entity\Security\User;
use App\Form\Postgrado\RolComisionType;
use App\Repository\Postgrado\RolComisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/rol_comision")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class RolComisionController extends AbstractController
{

    /**
     * @Route("/", name="app_rol_comision_index", methods={"GET"})
     * @param RolComisionRepository $rolComisionRepository
     * @return Response
     */
    public function index(RolComisionRepository $rolComisionRepository)
    {
        return $this->render('modules/postgrado/rol_comision/index.html.twig', [
            'registros' => $rolComisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_rol_comision_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param RolComisionRepository $rolComisionRepository
     * @return Response
     */
    public function registrar(Request $request, RolComisionRepository $rolComisionRepository)
    {
        try {
            $catDocenteEntity = new RolComision();
            $form = $this->createForm(RolComisionType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $rolComisionRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_rol_comision_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/rol_comision/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_comision_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_rol_comision_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $rolComision
     * @param RolComisionRepository $rolComisionRepository
     * @return Response
     */
    public function modificar(Request $request, RolComision $rolComision, RolComisionRepository $rolComisionRepository)
    {
        try {
            $form = $this->createForm(RolComisionType::class, $rolComision, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $rolComisionRepository->edit($rolComision);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rol_comision_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/rol_comision/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_comision_modificar', ['id' => $rolComision], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_rol_comision_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $rolComision
     * @param RolComisionRepository $rolComisionRepository
     * @return Response
     */
    public function detail(Request $request, RolComision $rolComision)
    {
        return $this->render('modules/postgrado/rol_comision/detail.html.twig', [
            'item' => $rolComision,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_rol_comision_eliminar", methods={"GET"})
     * @param Request $request
     * @param RolComision $rolComision
     * @param RolComisionRepository $rolComisionRepository
     * @return Response
     */
    public function eliminar(Request $request, RolComision $rolComision, RolComisionRepository $rolComisionRepository)
    {
        try {
            if ($rolComisionRepository->find($rolComision) instanceof RolComision) {
                $rolComisionRepository->remove($rolComision, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rol_comision_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rol_comision_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_comision_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
