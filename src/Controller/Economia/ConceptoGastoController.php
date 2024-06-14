<?php

namespace App\Controller\Economia;

use App\Entity\Economia\ConceptoGasto;
use App\Entity\Security\User;
use App\Form\Economia\ConceptoGastoType;
use App\Repository\Economia\ConceptoGastoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/economia/concepto_gasto")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CONCEPTO_GASTO")
 */
class ConceptoGastoController extends AbstractController
{

    /**
     * @Route("/", name="app_concepto_gasto_index", methods={"GET"})
     * @param conceptoGastoRepository $conceptoGastoRepository
     * @return Response
     */
    public function index(ConceptoGastoRepository $conceptoGastoRepository)
    {
        return $this->render('modules/economia/concepto_gasto/index.html.twig', [
            'registros' => $conceptoGastoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_concepto_gasto_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param conceptoGastoRepository $conceptoGastoRepository
     * @return Response
     */
    public function registrar(Request $request, ConceptoGastoRepository $conceptoGastoRepository)
    {
        try {
            $entidad = new conceptoGasto();
            $form = $this->createForm(ConceptoGastoType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $conceptoGastoRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_concepto_gasto_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/economia/concepto_gasto/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_concepto_gasto_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_concepto_gasto_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $conceptoGasto
     * @param conceptoGastoRepository $conceptoGastoRepository
     * @return Response
     */
    public function modificar(Request $request, ConceptoGasto $conceptoGasto, ConceptoGastoRepository $conceptoGastoRepository)
    {
        try {
            $form = $this->createForm(ConceptoGastoType::class, $conceptoGasto, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $conceptoGastoRepository->edit($conceptoGasto);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_concepto_gasto_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/economia/concepto_gasto/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_concepto_gasto_modificar', ['id' => $conceptoGasto], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_concepto_gasto_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param conceptoGasto $conceptoGasto
     * @return Response
     */
    public function detail(Request $request, ConceptoGasto $conceptoGasto)
    {
        return $this->render('modules/economia/concepto_gasto/detail.html.twig', [
            'item' => $conceptoGasto,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_concepto_gasto_eliminar", methods={"GET"})
     * @param Request $request
     * @param conceptoGasto $conceptoGasto
     * @param conceptoGastoRepository $conceptoGastoRepository
     * @return Response
     */
    public function eliminar(Request $request, ConceptoGasto $conceptoGasto, ConceptoGastoRepository $conceptoGastoRepository)
    {
        try {
            if ($conceptoGastoRepository->find($conceptoGasto) instanceof ConceptoGasto) {
                $conceptoGastoRepository->remove($conceptoGasto, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_concepto_gasto_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_concepto_gasto_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_concepto_gasto_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
