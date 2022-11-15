<?php

namespace App\Controller\Institucion;

use App\Entity\Institucion\Institucion;
use App\Entity\Security\User;
use App\Form\Institucion\InstitucionType;
use App\Repository\Institucion\InstitucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/institucion/institucion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class InstitucionController extends AbstractController
{

    /**
     * @Route("/", name="app_institucion_index", methods={"GET"})
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function index(InstitucionRepository $tipoInstitucionRepository)
    {
        return $this->render('modules/institucion/institucion/index.html.twig', [
            'registros' => $tipoInstitucionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_institucion_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function registrar(Request $request, InstitucionRepository $tipoInstitucionRepository)
    {
//        try {
            $catDocenteEntity = new Institucion();
            $form = $this->createForm(InstitucionType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoInstitucionRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/new.html.twig', [
                'form' => $form->createView(),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_institucion_registrar', [], Response::HTTP_SEE_OTHER);
//        }
    }


    /**
     * @Route("/{id}/modificar", name="app_institucion_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoInstitucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function modificar(Request $request, Institucion $tipoInstitucion, InstitucionRepository $tipoInstitucionRepository)
    {
        try {
            $form = $this->createForm(InstitucionType::class, $tipoInstitucion, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoInstitucionRepository->edit($tipoInstitucion);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_modificar', ['id' => $tipoInstitucion], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_institucion_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoInstitucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function detail(Request $request, Institucion $tipoInstitucion)
    {
        return $this->render('modules/institucion/institucion/detail.html.twig', [
            'item' => $tipoInstitucion,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_institucion_eliminar", methods={"GET"})
     * @param Request $request
     * @param Institucion $tipoInstitucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function eliminar(Request $request, Institucion $tipoInstitucion, InstitucionRepository $tipoInstitucionRepository)
    {
        try {
            if ($tipoInstitucionRepository->find($tipoInstitucion) instanceof Institucion) {
                $tipoInstitucionRepository->remove($tipoInstitucion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
