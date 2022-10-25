<?php

namespace App\Controller\Traza;

use App\Entity\Traza\TipoTraza;
use App\Entity\Security\User;
use App\Form\Traza\TipoTrazaType;
use App\Repository\Traza\TipoTrazaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/traza/tipo_traza")
 * @IsGranted("ROLE_ADMIN", "ROLE_TYPTRZ")
 */
class TipoTrazaController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_traza_index", methods={"GET"})
     * @param TipoTrazaRepository $tipoTrazaRepository
     * @return Response
     */
    public function index(TipoTrazaRepository $tipoTrazaRepository)
    {

        return $this->render('modules/traza/tipo_traza/index.html.twig', [
            'registros' => $tipoTrazaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);

    }

    /**
     * @Route("/registrar", name="app_tipo_traza_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoTrazaRepository $tipoTrazaRepository
     * @return Response
     */
    public function registrar(Request $request, TipoTrazaRepository $tipoTrazaRepository)
    {
        try {
            $catDocenteEntity = new TipoTraza();
            $form = $this->createForm(TipoTrazaType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoTrazaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_traza_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/traza/tipo_traza/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_traza_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_traza_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoTraza $tipoTraza
     * @param TipoTrazaRepository $tipoTrazaRepository
     * @return Response
     */
    public function modificar(Request $request, TipoTraza $tipoTraza, TipoTrazaRepository $tipoTrazaRepository)
    {
        try {
            $form = $this->createForm(TipoTrazaType::class, $tipoTraza, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoTraza->setDescripcion($form->get('descripcion')->getData());
                $tipoTrazaRepository->edit($tipoTraza, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_traza_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/traza/tipo_traza/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_traza_modificar', ['id' => $tipoTraza], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_traza_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoTraza
     * @param TipoTrazaRepository $tipoTrazaRepository
     * @return Response
     */
    public function detail(Request $request, TipoTraza $tipoTraza)
    {
        return $this->render('modules/traza/tipo_traza/detail.html.twig', [
            'item' => $tipoTraza,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_traza_eliminar", methods={"GET"})
     * @param Request $request
     * @param TipoTraza $tipoTraza
     * @param TipoTrazaRepository $tipoTrazaRepository
     * @return Response
     */
    public function eliminar(Request $request, TipoTraza $tipoTraza, TipoTrazaRepository $tipoTrazaRepository)
    {
        try {
            if ($tipoTrazaRepository->find($tipoTraza) instanceof TipoTraza) {
                $tipoTrazaRepository->remove($tipoTraza, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_traza_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_traza_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_traza_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
