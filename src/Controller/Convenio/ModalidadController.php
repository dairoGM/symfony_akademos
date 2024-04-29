<?php

namespace App\Controller\Convenio;

use App\Entity\Convenio\Convenio;
use App\Entity\Convenio\Modalidad;
use App\Form\Convenio\ModalidadType;
use App\Repository\Convenio\ModalidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/convenio/modalidad")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MODALIDAD")
 */
class ModalidadController extends AbstractController
{

    /**
     * @Route("/", name="app_modalidad_index", methods={"GET"})
     * @param ModalidadRepository $modalidadRepository
     * @return Response
     */
    public function index(ModalidadRepository $modalidadRepository)
    {
        return $this->render('modules/convenio/modalidad/index.html.twig', [
            'registros' => $modalidadRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_modalidad_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ModalidadRepository $modalidadRepository
     * @return Response
     */
    public function registrar(Request $request, ModalidadRepository $modalidadRepository)
    {
        try {
            $entidad = new Modalidad();
            $form = $this->createForm(ModalidadType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $modalidadRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/convenio/modalidad/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modalidad_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_modalidad_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Convenio $modalidad
     * @param ModalidadRepository $modalidadRepository
     * @return Response
     */
    public function modificar(Request $request, Modalidad $modalidad, ModalidadRepository $modalidadRepository)
    {
        try {
            $form = $this->createForm(ModalidadType::class, $modalidad, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $modalidadRepository->edit($modalidad);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/convenio/modalidad/edit.html.twig', [
                'form' => $form->createView(),
                'convenio' => $modalidad
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modalidad_modificar', ['id' => $modalidad->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_modalidad_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Convenio $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, Convenio $convenio)
    {
        return $this->render('modules/convenio/modalidad/detail.html.twig', [
            'item' => $convenio,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_modalidad_eliminar", methods={"GET"})
     * @param Request $request
     * @param Convenio $convenio
     * @param ModalidadRepository $modalidadRepository
     * @return Response
     */
    public function eliminar(Request $request, Convenio $convenio, ModalidadRepository $modalidadRepository)
    {
        try {
            if ($modalidadRepository->find($convenio) instanceof Modalidad) {
                $modalidadRepository->remove($convenio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
