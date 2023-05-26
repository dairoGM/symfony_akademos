<?php

namespace App\Controller\Api;

use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Estructura\ResponsabilidadRepository;
use App\Repository\Institucion\InstitucionEditorialRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Institucion\InstitucionRevistaCientificaRepository;
use Proxies\__CG__\App\Entity\Estructura\Responsabilidad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiResponsabilidadController extends AbstractController
{

    /**
     * @Route("//api/listar_responsabilides", name="api_listar_responsabilides", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return JsonResponse
     */
    public function listarResponsabilidades(Request $request, ResponsabilidadRepository $responsabilidadRepository)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
//            $filtros['activo'] = true;
//            if (isset($jsonParams['activo'])) {
//                $filtros['activo'] = $jsonParams['activo'];
//            }
            $result = $responsabilidadRepository->findBy(['activo' => true], ['nombre' => 'asc']);


            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
