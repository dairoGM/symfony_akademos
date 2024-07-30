<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\SistemaOperativo;
use App\Form\Informatizacion\SistemaOperativoType;
use App\Repository\Informatizacion\SistemaOperativoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/sistema_operativo")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_SISTEMA_OPERATIVO")
 */
class SistemaOperativoController extends AbstractController
{

    /**
     * @Route("/", name="app_sistema_operativo_index", methods={"GET"})
     * @param SistemaOperativoRepository $SistemaOperativoRepository
     * @return Response
     */
    public function index(SistemaOperativoRepository $SistemaOperativoRepository)
    {
        return $this->render('modules/informatizacion/sistemaOperativo/index.html.twig', [
            'registros' => $SistemaOperativoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_sistema_operativo_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SistemaOperativoRepository $sistemaOperativoRepository
     * @return Response
     */
    public function registrar(Request $request, SistemaOperativoRepository $sistemaOperativoRepository)
    {
        try {
            $entidad = new SistemaOperativo();
            $form = $this->createForm(SistemaOperativoType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sistemaOperativoRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_sistema_operativo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/sistemaOperativo/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_sistema_operativo_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_sistema_operativo_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param SistemaOperativo $sistemaOperativo
     * @param SistemaOperativoRepository $sistemaOperativoRepository
     * @return Response
     */
    public function modificar(Request $request, SistemaOperativo $sistemaOperativo, SistemaOperativoRepository $sistemaOperativoRepository)
    {
        try {
            $form = $this->createForm(SistemaOperativoType::class, $sistemaOperativo, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $sistemaOperativoRepository->edit($sistemaOperativo);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_sistema_operativo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/sistemaOperativo/edit.html.twig', [
                'form' => $form->createView(),
                'sistemaOperativo' => $sistemaOperativo
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_sistema_operativo_modificar', ['id' => $sistemaOperativo->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_sistema_operativo_detail", methods={"GET", "POST"})
     * @param sistemaOperativo $sistemaOperativo
     * @return Response
     */
    public function detail(SistemaOperativo $sistemaOperativo)
    {
        return $this->render('modules/informatizacion/sistemaOperativo/detail.html.twig', [
            'item' => $sistemaOperativo,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_sistema_operativo_eliminar", methods={"GET"})
     * @param SistemaOperativo $sistemaOperativo
     * @param SistemaOperativoRepository $sistemaOperativoRepository
     * @return Response
     */
    public function eliminar(SistemaOperativo $sistemaOperativo, SistemaOperativoRepository $sistemaOperativoRepository)
    {
        try {
            if ($sistemaOperativoRepository->find($sistemaOperativo) instanceof SistemaOperativo) {
                $sistemaOperativoRepository->remove($sistemaOperativo, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_sistema_operativo_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_sistema_operativo_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_sistema_operativo_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
