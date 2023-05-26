<?php

namespace App\Controller\Api;

use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Institucion\InstitucionEditorialRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Institucion\InstitucionRevistaCientificaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiCategoriaEstructuraController extends AbstractController
{

    /**
     * @Route("/listar_categorias_estructuras", name="api_listar_categorias_estructuras", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param CategoriaEstructuraRepository $categoriaEstructuraRepository
     * @return JsonResponse
     */
    public function listarCatgoriasEstructuras(Request $request, CategoriaEstructuraRepository $categoriaEstructuraRepository)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
//            $filtros['activo'] = true;
//            if (isset($jsonParams['activo'])) {
//                $filtros['activo'] = $jsonParams['activo'];
//            }
            $result = $categoriaEstructuraRepository->findBy(['activo' => true], ['nombre' => 'asc']);


            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
