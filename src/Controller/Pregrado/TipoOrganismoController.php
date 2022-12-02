<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\TipoOrganismo;
use App\Entity\Security\User;
use App\Form\Pregrado\TipoOrganismoType;
use App\Repository\Pregrado\TipoOrganismoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/tipo_organismo")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class TipoOrganismoController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_organismo_index", methods={"GET"})
     * @param TipoOrganismoRepository $tipoProgramaRepository
     * @return Response
     */
    public function index(TipoOrganismoRepository $tipoProgramaRepository)
    {
        return $this->render('modules/pregrado/tipo_organismo/index.html.twig', [
            'registros' => $tipoProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_tipo_organismo_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoOrganismoRepository $tipoProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, TipoOrganismoRepository $tipoProgramaRepository)
    {
        try {
            $catDocenteEntity = new TipoOrganismo();
            $form = $this->createForm(TipoOrganismoType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_organismo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/tipo_organismo/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organismo_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_organismo_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoPrograma
     * @param TipoOrganismoRepository $tipoProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, TipoOrganismo $tipoPrograma, TipoOrganismoRepository $tipoProgramaRepository)
    {
        try {
            $form = $this->createForm(TipoOrganismoType::class, $tipoPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->edit($tipoPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_organismo_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/tipo_organismo/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organismo_modificar', ['id' => $tipoPrograma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_organismo_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoOrganismo $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, TipoOrganismo $tipoProgramaAcademico)
    {
        return $this->render('modules/pregrado/tipo_organismo/detail.html.twig', [
            'item' => $tipoProgramaAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_organismo_eliminar", methods={"GET"})
     * @param Request $request
     * @param TipoOrganismo $tipoPrograma
     * @param TipoOrganismoRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, TipoOrganismo $tipoPrograma, TipoOrganismoRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($tipoPrograma) instanceof TipoOrganismo) {
                $tipoProgramaRepository->remove($tipoPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_organismo_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_organismo_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_organismo_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
