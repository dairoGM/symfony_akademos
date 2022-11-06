<?php

namespace App\Controller\Institucion;

use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Security\User;
use App\Form\Institucion\NivelAcreditacionType;
use App\Repository\Institucion\NivelAcreditacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/institucion/nivel_acreditacion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class NivelAcreditacionController extends AbstractController
{

    /**
     * @Route("/", name="app_nivel_acreditacion_index", methods={"GET"})
     * @param NivelAcreditacionRepository $nivelAcreditacionRepository
     * @return Response
     */
    public function index(NivelAcreditacionRepository $nivelAcreditacionRepository)
    {
        return $this->render('modules/institucion/nivel_acreditacion/index.html.twig', [
            'registros' => $nivelAcreditacionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_nivel_acreditacion_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param NivelAcreditacionRepository $nivelAcreditacionRepository
     * @return Response
     */
    public function registrar(Request $request, NivelAcreditacionRepository $nivelAcreditacionRepository)
    {
//        try {
            $catDocenteEntity = new NivelAcreditacion();
            $form = $this->createForm(NivelAcreditacionType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $nivelAcreditacionRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_nivel_acreditacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/nivel_acreditacion/new.html.twig', [
                'form' => $form->createView(),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_nivel_acreditacion_registrar', [], Response::HTTP_SEE_OTHER);
//        }
    }


    /**
     * @Route("/{id}/modificar", name="app_nivel_acreditacion_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $nivelAcreditacion
     * @param NivelAcreditacionRepository $nivelAcreditacionRepository
     * @return Response
     */
    public function modificar(Request $request, NivelAcreditacion $nivelAcreditacion, NivelAcreditacionRepository $nivelAcreditacionRepository)
    {
//        try {
            $form = $this->createForm(NivelAcreditacionType::class, $nivelAcreditacion, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $nivelAcreditacionRepository->edit($nivelAcreditacion);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_nivel_acreditacion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/nivel_acreditacion/edit.html.twig', [
                'form' => $form->createView(),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_nivel_acreditacion_modificar', ['id' => $nivelAcreditacion], Response::HTTP_SEE_OTHER);
//        }
    }


    /**
     * @Route("/{id}/detail", name="app_nivel_acreditacion_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $nivelAcreditacion
     * @param NivelAcreditacionRepository $nivelAcreditacionRepository
     * @return Response
     */
    public function detail(Request $request, NivelAcreditacion $nivelAcreditacion)
    {
        return $this->render('modules/institucion/nivel_acreditacion/detail.html.twig', [
            'item' => $nivelAcreditacion,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_nivel_acreditacion_eliminar", methods={"GET"})
     * @param Request $request
     * @param NivelAcreditacion $nivelAcreditacion
     * @param NivelAcreditacionRepository $nivelAcreditacionRepository
     * @return Response
     */
    public function eliminar(Request $request, NivelAcreditacion $nivelAcreditacion, NivelAcreditacionRepository $nivelAcreditacionRepository)
    {
        try {
            if ($nivelAcreditacionRepository->find($nivelAcreditacion) instanceof NivelAcreditacion) {
                $nivelAcreditacionRepository->remove($nivelAcreditacion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_nivel_acreditacion_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_nivel_acreditacion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nivel_acreditacion_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
