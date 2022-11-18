<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Municipio;
use App\Entity\Security\User;
use App\Form\Estructura\MunicipioType;
use App\Repository\Estructura\MunicipioRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/municipio")
 */
class MunicipioController extends AbstractController
{

    /**
     * @Route("/", name="app_municipio_index", methods={"GET"})
     * @param MunicipioRepository $municipioRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MUNICP")
     */
    public function index(MunicipioRepository $municipioRepository)
    {
        try {
            return $this->render('modules/estructura/municipio/index.html.twig', [
                'registros' => $municipioRepository->findBy([], ['codigo' => 'asc', 'activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_municipio_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_municipio_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param MunicipioRepository $municipioRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MUNICP")
     */
    public function registrar(Request $request, MunicipioRepository $municipioRepository)
    {
        try {
            $catMunicipioEntity = new Municipio();
            $form = $this->createForm(MunicipioType::class, $catMunicipioEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $municipioRepository->add($catMunicipioEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_municipio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/municipio/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_municipio_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_municipio_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $municipio
     * @param MunicipioRepository $municipioRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MUNICP")
     */
    public function modificar(Request $request, Municipio $municipio, MunicipioRepository $municipioRepository)
    {
        try {
            $form = $this->createForm(MunicipioType::class, $municipio, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $municipioRepository->edit($municipio);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_municipio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/municipio/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_municipio_modificar', ['id' => $municipio], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/eliminar", name="app_municipio_eliminar", methods={"GET"})
     * @param Request $request
     * @param Municipio $municipio
     * @param MunicipioRepository $municipioRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MUNICP")
     */
    public function eliminar(Request $request, Municipio $municipio, MunicipioRepository $municipioRepository)
    {
        try {
            if ($municipioRepository->find($municipio) instanceof Municipio) {
                $municipioRepository->remove($municipio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_municipio_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_municipio_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_municipio_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_municipio_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Municipio $municipio
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_MUNICP")
     */
    public function detail(Request $request, Municipio $municipio)
    {
        return $this->render('modules/estructura/municipio/detail.html.twig', [
            'item' => $municipio,
        ]);
    }

    /**
     * Add package entity.
     *
     * @Route("/{id}/municipio_dado_provinvia", name="app_municipio_dado_provincia", methods={"GET"})
     * @param Request $request
     * @param $id
     * @param MunicipioRepository $municipioRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getMunicipiosDadoProvincia(Request $request, $id, MunicipioRepository $municipioRepository, Utils $utils): JsonResponse
    {
        try {
            return $this->json($utils->procesarNomenclador($municipioRepository->findBy(['provincia' => $id], ['nombre' => 'asc'])));
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }
}
