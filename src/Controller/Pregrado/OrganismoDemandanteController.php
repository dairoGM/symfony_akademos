<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\OrganismoDemandante;
use App\Entity\Security\User;
use App\Form\Pregrado\OrganismoDemandanteType;
use App\Repository\Pregrado\OrganismoDemandanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/organismo_demandante")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class OrganismoDemandanteController extends AbstractController
{

    /**
     * @Route("/", name="app_organismo_demandante_index", methods={"GET"})
     * @param OrganismoDemandanteRepository $tipoProgramaRepository
     * @return Response
     */
    public function index(OrganismoDemandanteRepository $tipoProgramaRepository)
    {
        return $this->render('modules/pregrado/organismo_demandante/index.html.twig', [
            'registros' => $tipoProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_organismo_demandante_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param OrganismoDemandanteRepository $tipoProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, OrganismoDemandanteRepository $tipoProgramaRepository)
    {
        try {
            $catDocenteEntity = new OrganismoDemandante();
            $form = $this->createForm(OrganismoDemandanteType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_organismo_demandante_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/organismo_demandante/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_organismo_demandante_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_organismo_demandante_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoPrograma
     * @param OrganismoDemandanteRepository $tipoProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, OrganismoDemandante $tipoPrograma, OrganismoDemandanteRepository $tipoProgramaRepository)
    {
        try {
            $form = $this->createForm(OrganismoDemandanteType::class, $tipoPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->edit($tipoPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_organismo_demandante_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/organismo_demandante/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_organismo_demandante_modificar', ['id' => $tipoPrograma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_organismo_demandante_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param OrganismoDemandante $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, OrganismoDemandante $tipoProgramaAcademico)
    {
        return $this->render('modules/pregrado/organismo_demandante/detail.html.twig', [
            'item' => $tipoProgramaAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_organismo_demandante_eliminar", methods={"GET"})
     * @param Request $request
     * @param OrganismoDemandante $tipoPrograma
     * @param OrganismoDemandanteRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, OrganismoDemandante $tipoPrograma, OrganismoDemandanteRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($tipoPrograma) instanceof OrganismoDemandante) {
                $tipoProgramaRepository->remove($tipoPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_organismo_demandante_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_organismo_demandante_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_organismo_demandante_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
