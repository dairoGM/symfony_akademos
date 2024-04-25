<?php

namespace App\Controller\Tramite;

use App\Entity\Tramite\TipoPasaporte;
use App\Entity\Security\User;
use App\Form\Tramite\TipoPasaporteType;
use App\Repository\Tramite\TipoPasaporteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/tipo_pasaporte")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATACRED")
 */
class TipoPasaporteController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_pasaporte_index", methods={"GET"})
     * @param tipoPasaporteRepository $tipoPasaporteRepository
     * @return Response
     */
    public function index(TipoPasaporteRepository $tipoPasaporteRepository)
    {
        return $this->render('modules/tramite/tipo_pasaporte/index.html.twig', [
            'registros' => $tipoPasaporteRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_tipo_pasaporte_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param tipoPasaporteRepository $tipoPasaporteRepository
     * @return Response
     */
    public function registrar(Request $request, TipoPasaporteRepository $tipoPasaporteRepository)
    {
        try {
            $entidad = new TipoPasaporte();
            $form = $this->createForm(TipoPasaporteType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoPasaporteRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/tipo_pasaporte/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_pasaporte_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_pasaporte_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoPasaporte $tipoPasaporte
     * @param tipoPasaporteRepository $tipoPasaporteRepository
     * @return Response
     */
    public function modificar(Request $request, TipoPasaporte $tipoPasaporte, TipoPasaporteRepository $tipoPasaporteRepository)
    {
        try {
            $form = $this->createForm(TipoPasaporteType::class, $tipoPasaporte, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoPasaporteRepository->edit($tipoPasaporte);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/tipo_pasaporte/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_pasaporte_modificar', ['id' => $tipoPasaporte], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_pasaporte_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param tipoPasaporte $tipoPasaporte
     * @return Response
     */
    public function detail(Request $request, TipoPasaporte $tipoPasaporte)
    {
        return $this->render('modules/tramite/tipo_pasaporte/detail.html.twig', [
            'item' => $tipoPasaporte,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_pasaporte_eliminar", methods={"GET"})
     * @param tipoPasaporte $tipoPasaporte
     * @param tipoPasaporteRepository $tipoPasaporteRepository
     * @return Response
     */
    public function eliminar(TipoPasaporte $tipoPasaporte, TipoPasaporteRepository $tipoPasaporteRepository)
    {
        try {
            if ($tipoPasaporteRepository->find($tipoPasaporte) instanceof TipoPasaporte) {
                $tipoPasaporteRepository->remove($tipoPasaporte, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
