<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\Oace;
use App\Entity\Pregrado\OrganismoFormador;
use App\Entity\Security\User;
use App\Form\Pregrado\OaceType;
use App\Form\Pregrado\OrganismoFormadorType;
use App\Repository\Pregrado\OaceRepository;
use App\Repository\Pregrado\OrganismoFormadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/organismo_formador")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class OrganismoFormadorController extends AbstractController
{

    /**
     * @Route("/", name="app_organismo_formador_index", methods={"GET"})
     * @param OrganismoFormadorRepository $organismoFormadorRepository
     * @return Response
     */
    public function index(OrganismoFormadorRepository $organismoFormadorRepository)
    {
        return $this->render('modules/pregrado/organismo_formador/index.html.twig', [
            'registros' => $organismoFormadorRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_organismo_formador_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param OrganismoFormadorRepository $organismoFormadorRepository
     * @return Response
     */
    public function registrar(Request $request, OrganismoFormadorRepository $organismoFormadorRepository)
    {
        try {
            $organismoFormador = new OrganismoFormador();
            $form = $this->createForm(OrganismoFormadorType::class, $organismoFormador, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $organismoFormadorRepository->add($organismoFormador, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_organismo_formador_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/organismo_formador/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_organismo_formador_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_organismo_formador_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param OrganismoFormador $organismoFormador
     * @param OrganismoFormadorRepository $organismoFormadorRepository
     * @return Response
     */
    public function modificar(Request $request, OrganismoFormador $organismoFormador, OrganismoFormadorRepository $organismoFormadorRepository)
    {
        try {
            $form = $this->createForm(OrganismoFormadorType::class, $organismoFormador, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $organismoFormadorRepository->edit($organismoFormador);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_organismo_formador_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/organismo_formador/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_organismo_formador_modificar', ['id' => $organismoFormador], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_organismo_formador_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param OrganismoFormador $organismoFormador
     * @return Response
     */
    public function detail(Request $request, OrganismoFormador $organismoFormador)
    {
        return $this->render('modules/pregrado/organismo_formador/detail.html.twig', [
            'item' => $organismoFormador,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_organismo_formador_eliminar", methods={"GET"})
     * @param OrganismoFormador $organismoFormador
     * @param OrganismoFormadorRepository $organismoFormadorRepository
     * @return Response
     */
    public function eliminar(OrganismoFormador $organismoFormador, OrganismoFormadorRepository $organismoFormadorRepository)
    {
        try {
            if ($organismoFormadorRepository->find($organismoFormador) instanceof OrganismoFormador) {
                $organismoFormadorRepository->remove($organismoFormador, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_organismo_formador_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_organismo_formador_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_organismo_formador_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
