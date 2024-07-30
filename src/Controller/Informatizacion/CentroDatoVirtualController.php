<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\CentroDatoVirtual;
use App\Form\Informatizacion\CentroDatoVirtualType;
use App\Repository\Informatizacion\CentroDatoVirtualRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/centro_dato_virtual")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CENTRO_DATO_VIRTUAL")
 */
class CentroDatoVirtualController extends AbstractController
{

    /**
     * @Route("/", name="app_centro_dato_virtual_index", methods={"GET"})
     * @param CentroDatoVirtualRepository $centroDatoVirtualRepository
     * @return Response
     */
    public function index(CentroDatoVirtualRepository $centroDatoVirtualRepository)
    {
        return $this->render('modules/informatizacion/centroDatoVirtual/index.html.twig', [
            'registros' => $centroDatoVirtualRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_centro_dato_virtual_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CentroDatoVirtualRepository $centroDatoVirtualRepository
     * @return Response
     */
    public function registrar(Request $request, CentroDatoVirtualRepository $centroDatoVirtualRepository)
    {
        try {
            $entidad = new CentroDatoVirtual();
            $form = $this->createForm(CentroDatoVirtualType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $centroDatoVirtualRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_centro_dato_virtual_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/centroDatoVirtual/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_centro_dato_virtual_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_centro_dato_virtual_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param CentroDatoVirtual $centroDatoVirtual
     * @param CentroDatoVirtualRepository $centroDatoVirtualRepository
     * @return Response
     */
    public function modificar(Request $request, CentroDatoVirtual $centroDatoVirtual, CentroDatoVirtualRepository $centroDatoVirtualRepository)
    {
        try {
            $form = $this->createForm(CentroDatoVirtualType::class, $centroDatoVirtual, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $centroDatoVirtualRepository->edit($centroDatoVirtual);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_centro_dato_virtual_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/centroDatoVirtual/edit.html.twig', [
                'form' => $form->createView(),
                'centroDatoVirtual' => $centroDatoVirtual
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_centro_dato_virtual_modificar', ['id' => $centroDatoVirtual->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_centro_dato_virtual_detail", methods={"GET", "POST"})
     * @param centroDatoVirtual $centroDatoVirtual
     * @return Response
     */
    public function detail(CentroDatoVirtual $centroDatoVirtual)
    {
        return $this->render('modules/informatizacion/centroDatoVirtual/detail.html.twig', [
            'item' => $centroDatoVirtual,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_centro_dato_virtual_eliminar", methods={"GET"})
     * @param CentroDatoVirtual $centroDatoVirtual
     * @param CentroDatoVirtualRepository $centroDatoVirtualRepository
     * @return Response
     */
    public function eliminar(CentroDatoVirtual $centroDatoVirtual, CentroDatoVirtualRepository $centroDatoVirtualRepository)
    {
        try {
            if ($centroDatoVirtualRepository->find($centroDatoVirtual) instanceof CentroDatoVirtual) {
                $centroDatoVirtualRepository->remove($centroDatoVirtual, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_centro_dato_virtual_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_centro_dato_virtual_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_centro_dato_virtual_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
