<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\TipoPrograma;
use App\Entity\Security\User;
use App\Form\Postgrado\TipoProgramaType;
use App\Repository\Postgrado\TipoProgramaRepository;
use App\Repository\Security\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/tipo_programa")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_TIPPROGRAM")
 */
class TipoProgramaController extends AbstractController
{

    /**
     * @Route("/", name="app_tipo_programa_index", methods={"GET"})
     * @param TipoProgramaRepository $tipoProgramaRepository
     * @return Response
     */
    public function index(TipoProgramaRepository $tipoProgramaRepository)
    {
        return $this->render('modules/postgrado/tipo_programa/index.html.twig', [
            'registros' => $tipoProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_tipo_programa_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param TipoProgramaRepository $tipoProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, TipoProgramaRepository $tipoProgramaRepository)
    {
        try {
            $catDocenteEntity = new TipoPrograma();
            $form = $this->createForm(TipoProgramaType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/tipo_programa/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_programa_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_tipo_programa_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoPrograma
     * @param TipoProgramaRepository $tipoProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, TipoPrograma $tipoPrograma, TipoProgramaRepository $tipoProgramaRepository)
    {
        try {
            $form = $this->createForm(TipoProgramaType::class, $tipoPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoProgramaRepository->edit($tipoPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/tipo_programa/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_programa_modificar', ['id' => $tipoPrograma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_tipo_programa_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $tipoPrograma
     * @param TipoProgramaRepository $tipoProgramaRepository
     * @return Response
     */
    public function detail(Request $request, TipoPrograma $tipoPrograma)
    {
        return $this->render('modules/postgrado/tipo_programa/detail.html.twig', [
            'item' => $tipoPrograma,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_tipo_programa_eliminar", methods={"GET"})
     * @param Request $request
     * @param TipoPrograma $tipoPrograma
     * @param TipoProgramaRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, TipoPrograma $tipoPrograma, TipoProgramaRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($tipoPrograma) instanceof TipoPrograma) {
                $tipoProgramaRepository->remove($tipoPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_tipo_programa_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_tipo_programa_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_tipo_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
