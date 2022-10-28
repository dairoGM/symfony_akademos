<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\Comision;
use App\Entity\Security\User;
use App\Form\Postgrado\ComisionType;
use App\Repository\Postgrado\ComisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/comision")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class ComisionController extends AbstractController
{

    /**
     * @Route("/", name="app_comision_index", methods={"GET"})
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function index(ComisionRepository $comisionRepository)
    {
        return $this->render('modules/postgrado/comision/index.html.twig', [
            'registros' => $comisionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_comision_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function registrar(Request $request, ComisionRepository $comisionRepository)
    {
        try {
            $catDocenteEntity = new Comision();
            $form = $this->createForm(ComisionType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comisionRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/comision/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_comision_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $comision
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function modificar(Request $request, Comision $comision, ComisionRepository $comisionRepository)
    {
        try {
            $form = $this->createForm(ComisionType::class, $comision, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comisionRepository->edit($comision);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/comision/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_modificar', ['id' => $comision], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_comision_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $comision
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function detail(Request $request, Comision $comision)
    {
        return $this->render('modules/postgrado/comision/detail.html.twig', [
            'item' => $comision,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_comision_eliminar", methods={"GET"})
     * @param Request $request
     * @param Comision $comision
     * @param ComisionRepository $comisionRepository
     * @return Response
     */
    public function eliminar(Request $request, Comision $comision, ComisionRepository $comisionRepository)
    {
        try {
            if ($comisionRepository->find($comision) instanceof Comision) {
                $comisionRepository->remove($comision, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
