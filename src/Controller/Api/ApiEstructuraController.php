<?php

namespace App\Controller\Api;

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
class ApiEstructuraController extends AbstractController
{

    /**
     * @Route("/listar_estructuras", name="api_listar_estructuras", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param EstructuraRepository $estructuraRepository
     * @return JsonResponse
     */
    public function listarEstructuras(Request $request, EstructuraRepository $estructuraRepository)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
//            $filtros['activo'] = true;
//            if (isset($jsonParams['activo'])) {
//                $filtros['activo'] = $jsonParams['activo'];
//            }
            $result = $estructuraRepository->findBy(['activo' => true], ['nombre' => 'asc']);


            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }


    /**
     * @Route("/get_estructuras_dado_id_categoria", name="api_get_estructuras_dado_id_categoria", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param EstructuraRepository $estructuraRepository
     * @return JsonResponse
     */
    public function getEstructurasDadoIdCategoria(Request $request, EstructuraRepository $estructuraRepository)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
            $result = [];
            if (isset($jsonParams['id_categoria']) && !empty($jsonParams['id_categoria'])) {
                $result = $estructuraRepository->getEstructurasDadoIdCategoria($jsonParams['id_categoria']);
            }
            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
}
