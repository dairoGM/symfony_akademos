<?php

namespace App\Controller\Tramite;

use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Security\User;
use App\Form\Tramite\InstitucionExtranjeraType;
use App\Repository\Estructura\PlazaRepository;
use App\Repository\Tramite\InstitucionExtranjeraRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/convenio/institucion_extranjera")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_INST_EXTRANJERA")
 */
class InstitucionExtranjeraController extends AbstractController
{

    /**
     * @Route("/", name="app_institucion_extranjera_index", methods={"GET"})
     * @param institucionExtranjeraRepository $institucionExtranjeraRepository
     * @return Response
     */
    public function index(InstitucionExtranjeraRepository $institucionExtranjeraRepository)
    {
        return $this->render('modules/tramite/institucion_extranjera/index.html.twig', [
            'registros' => $institucionExtranjeraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_institucion_extranjera_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param institucionExtranjeraRepository $institucionExtranjeraRepository
     * @return Response
     */
    public function registrar(Request $request, InstitucionExtranjeraRepository $institucionExtranjeraRepository)
    {
        try {
            $entidad = new InstitucionExtranjera();
            $form = $this->createForm(InstitucionExtranjeraType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $institucionExtranjeraRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_extranjera_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/institucion_extranjera/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_extranjera_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_institucion_extranjera_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param InstitucionExtranjera $institucionExtranjera
     * @param institucionExtranjeraRepository $institucionExtranjeraRepository
     * @return Response
     */
    public function modificar(Request $request, InstitucionExtranjera $institucionExtranjera, InstitucionExtranjeraRepository $institucionExtranjeraRepository)
    {
        try {
            $form = $this->createForm(InstitucionExtranjeraType::class, $institucionExtranjera, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $institucionExtranjeraRepository->edit($institucionExtranjera);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_extranjera_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/institucion_extranjera/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_extranjera_modificar', ['id' => $institucionExtranjera], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_institucion_extranjera_detail", methods={"GET", "POST"})
     * @param institucionExtranjera $institucionExtranjera
     * @return Response
     */
    public function detail(InstitucionExtranjera $institucionExtranjera)
    {
        return $this->render('modules/tramite/institucion_extranjera/detail.html.twig', [
            'item' => $institucionExtranjera,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_institucion_extranjera_eliminar", methods={"GET"})
     * @param institucionExtranjera $institucionExtranjera
     * @param institucionExtranjeraRepository $institucionExtranjeraRepository
     * @return Response
     */
    public function eliminar(InstitucionExtranjera $institucionExtranjera, InstitucionExtranjeraRepository $institucionExtranjeraRepository)
    {
        try {
            if ($institucionExtranjeraRepository->find($institucionExtranjera) instanceof InstitucionExtranjera) {
                $institucionExtranjeraRepository->remove($institucionExtranjera, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_extranjera_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_extranjera_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_extranjera_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * Add package entity.
     *
     * @Route("/{id}/institucion_dado_pais", name="app_institucion_extranjera_institucion_dado_pais", methods={"GET"})
     * @param Request $request
     * @param $id
     * @param InstitucionExtranjeraRepository $institucionExtranjeraRepository
     * @return JsonResponse
     */
    public function getInstitucionDadoPais(Request $request, $id, InstitucionExtranjeraRepository $institucionExtranjeraRepository): JsonResponse
    {
        try {
            return $this->json($institucionExtranjeraRepository->findBy(['pais' => $id]));
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }
}
