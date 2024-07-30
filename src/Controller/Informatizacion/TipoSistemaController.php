<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\TipoSistema;
use App\Form\Informatizacion\TipoSistemaType;
use App\Repository\Informatizacion\TipoSistemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/tipo_sistema")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_TIPO_SISTEMA")
 */
class TipoSistemaController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_sistema_index", methods={"GET"})
     * @param TipoSistemaRepository $TipoSistemaRepository
     * @return Response
     */
    public function index(TipoSistemaRepository $TipoSistemaRepository)
    {
        return $this->render('modules/informatizacion/tipoSistema/index.html.twig', [
            'registros' => $TipoSistemaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_tipo_sistema_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoSistemaRepository $tipoSistemaRepository
     * @return Response
     */
    public function registrar(Request $request, TipoSistemaRepository $tipoSistemaRepository)
    {
        try {
            $entidad = new TipoSistema();
            $form = $this->createForm(TipoSistemaType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoSistemaRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_sistema_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/tipoSistema/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_sistema_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_sistema_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoSistema $tipoSistema
     * @param TipoSistemaRepository $tipoSistemaRepository
     * @return Response
     */
    public function modificar(Request $request, TipoSistema $tipoSistema, TipoSistemaRepository $tipoSistemaRepository)
    {
        try {
            $form = $this->createForm(TipoSistemaType::class, $tipoSistema, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoSistemaRepository->edit($tipoSistema);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_sistema_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/tipoSistema/edit.html.twig', [
                'form' => $form->createView(),
                'tipoSistema' => $tipoSistema
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_sistema_modificar', ['id' => $tipoSistema->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_sistema_detail", methods={"GET", "POST"})
     * @param tipoSistema $tipoSistema
     * @return Response
     */
    public function detail(TipoSistema $tipoSistema)
    {
        return $this->render('modules/informatizacion/tipoSistema/detail.html.twig', [
            'item' => $tipoSistema,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_sistema_eliminar", methods={"GET"})
     * @param TipoSistema $tipoSistema
     * @param TipoSistemaRepository $tipoSistemaRepository
     * @return Response
     */
    public function eliminar(TipoSistema $tipoSistema, TipoSistemaRepository $tipoSistemaRepository)
    {
        try {
            if ($tipoSistemaRepository->find($tipoSistema) instanceof TipoSistema) {
                $tipoSistemaRepository->remove($tipoSistema, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_sistema_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_sistema_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_sistema_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
