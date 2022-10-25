<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Responsabilidad;
use App\Entity\Security\User;
use App\Form\Estructura\ResponsabilidadType;
use App\Repository\Estructura\PlazaRepository;
use App\Repository\Estructura\ResponsabilidadRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/responsabilidad") * 
 */
class ResponsabilidadController extends AbstractController
{

    /**
     * @Route("/", name="app_responsabilidad_index", methods={"GET"})
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_RESPON")
     */
    public function index(ResponsabilidadRepository $responsabilidadRepository)
    {
        try {
            return $this->render('modules/estructura/responsabilidad/index.html.twig', [
                'registros' => $responsabilidadRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_responsabilidad_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_RESPON")
     */
    public function registrar(Request $request, ResponsabilidadRepository $responsabilidadRepository)
    {
        try {
            $catResponsabilidadEntity = new Responsabilidad();
            $form = $this->createForm(ResponsabilidadType::class, $catResponsabilidadEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $responsabilidadRepository->add($catResponsabilidadEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/responsabilidad/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_responsabilidad_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_responsabilidad_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $responsabilidad
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_RESPON")
     */
    public function modificar(Request $request, Responsabilidad $responsabilidad, ResponsabilidadRepository $responsabilidadRepository)
    {
        try {
            $form = $this->createForm(ResponsabilidadType::class, $responsabilidad, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $responsabilidadRepository->edit($responsabilidad);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/responsabilidad/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_responsabilidad_modificar', ['id' => $responsabilidad], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_responsabilidad_eliminar", methods={"GET"})
     * @param Request $request
     * @param Responsabilidad $responsabilidad
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_RESPON")
     */
    public function eliminar(Request $request, Responsabilidad $responsabilidad, ResponsabilidadRepository $responsabilidadRepository)
    {
        try {
            if ($responsabilidadRepository->find($responsabilidad) instanceof Responsabilidad) {
                $responsabilidadRepository->remove($responsabilidad, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_responsabilidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_responsabilidad_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Responsabilidad $responsabilidad
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_RESPON")
     */
    public function detail(Request $request, Responsabilidad $responsabilidad)
    {
        return $this->render('modules/estructura/responsabilidad/detail.html.twig', [
            'item' => $responsabilidad,
        ]);
    }

    /**
     * Add package entity.
     *
     * @Route("/{id}/responsabilidad_dado_estructura", name="app_responsabilidad_dado_estructura", methods={"GET"})
     * @param Request $request
     * @param $id
     * @param PlazaRepository $plazaRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getResponsabilidadDadoEstructura(Request $request, $id, PlazaRepository $plazaRepository, Utils $utils): JsonResponse
    {
        try {
            return $this->json($utils->procesarNomencladorResponsabilidad($plazaRepository->findBy(['estructura' => $id])));
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }
}
