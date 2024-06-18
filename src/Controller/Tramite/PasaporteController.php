<?php

namespace App\Controller\Tramite;

use App\Entity\Tramite\Pasaporte;
use App\Entity\Security\User;
use App\Form\Tramite\TramiteType;
use App\Repository\Tramite\PasaporteRepository;
use App\Repository\Tramite\TramiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/pasaporte")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PASAPORTE")
 */
class PasaporteController extends AbstractController
{

    /**
     * @Route("/", name="app_pasaporte_index", methods={"GET"})
     * @param pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function index(PasaporteRepository $pasaporteRepository)
    {
        return $this->render('modules/tramite/pasaporte/index.html.twig', [
            'registros' => $pasaporteRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_pasaporte_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function registrar(Request $request, PasaporteRepository $pasaporteRepository)
    {
        try {
            $entidad = new Pasaporte();
            $form = $this->createForm(PasaporteType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pasaporteRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/pasaporte/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_pasaporte_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Pasaporte $pasaporte
     * @param pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function modificar(Request $request, Pasaporte $pasaporte, PasaporteRepository $pasaporteRepository)
    {
        try {
            $form = $this->createForm(PasaporteType::class, $pasaporte, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $pasaporteRepository->edit($pasaporte);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pasaporte/pasaporte/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_modificar', ['id' => $pasaporte], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_pasaporte_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param pasaporte $pasaporte
     * @return Response
     */
    public function detail(Request $request, Pasaporte $pasaporte)
    {
        return $this->render('modules/tramite/pasaporte/detail.html.twig', [
            'item' => $pasaporte,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_pasaporte_eliminar", methods={"GET"})
     * @param Request $request
     * @param Tramite $pasaporte $pasaporte
     * @param PasaporteRepository $pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function eliminar(Request $request, Tramite $pasaporte, PasaporteRepository $pasaporteRepository)
    {
        try {
            if ($pasaporteRepository->find($pasaporte) instanceof Pasaporte) {
                $pasaporteRepository->remove($pasaporte, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
