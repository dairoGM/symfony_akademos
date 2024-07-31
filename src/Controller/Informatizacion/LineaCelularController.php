<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\LineaCelular;
use App\Form\Informatizacion\LineaCelularType;
use App\Repository\Informatizacion\LineaCelularRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/linea_celular")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_LINEA_CELULAR")
 */
class LineaCelularController extends AbstractController
{

    /**
     * @Route("/", name="app_linea_celular_index", methods={"GET"})
     * @param LineaCelularRepository $LineaCelularRepository
     * @return Response
     */
    public function index(LineaCelularRepository $LineaCelularRepository)
    {
        return $this->render('modules/informatizacion/lineaCelular/index.html.twig', [
            'registros' => $LineaCelularRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_linea_celular_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function registrar(Request $request, LineaCelularRepository $lineaCelularRepository)
    {
        try {
            $entidad = new LineaCelular();
            $form = $this->createForm(LineaCelularType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $lineaCelularRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/lineaCelular/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_linea_celular_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param LineaCelular $lineaCelular
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function modificar(Request $request, LineaCelular $lineaCelular, LineaCelularRepository $lineaCelularRepository)
    {
        try {
            $form = $this->createForm(LineaCelularType::class, $lineaCelular, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $lineaCelularRepository->edit($lineaCelular);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/lineaCelular/edit.html.twig', [
                'form' => $form->createView(),
                'lineaCelular' => $lineaCelular
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_modificar', ['id' => $lineaCelular->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_linea_celular_detail", methods={"GET", "POST"})
     * @param lineaCelular $lineaCelular
     * @return Response
     */
    public function detail(LineaCelular $lineaCelular)
    {
        return $this->render('modules/informatizacion/lineaCelular/detail.html.twig', [
            'item' => $lineaCelular,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_linea_celular_eliminar", methods={"GET"})
     * @param LineaCelular $lineaCelular
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function eliminar(LineaCelular $lineaCelular, LineaCelularRepository $lineaCelularRepository)
    {
        try {
            if ($lineaCelularRepository->find($lineaCelular) instanceof LineaCelular) {
                $lineaCelularRepository->remove($lineaCelular, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
