<?php

namespace App\Controller\Api;

use App\Repository\Institucion\InstitucionEditorialRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Institucion\InstitucionRevistaCientificaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiInstitucionesController extends AbstractController
{

    /**
     * @Route("/listar_instituciones", name="Api_listarInstituciones", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param InstitucionRepository $institucionRepository
     * @return JsonResponse
     */
    public function listarInstituciones(Request $request, InstitucionRepository $institucionRepository)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
//            $filtros['activo'] = true;
//            if (isset($jsonParams['activo'])) {
//                $filtros['activo'] = $jsonParams['activo'];
//            }
            $result = $institucionRepository->findAll();


            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), 403);
        }
    }

    /**
     * @Route("/listar_revistas", name="Api_listarRevistas", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param InstitucionRevistaCientificaRepository $revistaCientifica
     * @return JsonResponse
     */
    public function listarRevistas(Request $request, InstitucionRevistaCientificaRepository $revistaCientifica)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
//            $filtros['activo'] = true;
//            if (isset($jsonParams['activo'])) {
//                $filtros['activo'] = $jsonParams['activo'];
//            }
            $result = $revistaCientifica->getRevistasCiencificas();

            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), 403);
        }
    }


    /**
     * @Route("/listar_editorial", name="Api_listarEditorial", methods={"POST", "OPTIONS"}, defaults={"_format":"json"})
     * @param Request $request
     * @param InstitucionEditorialRepository $editorial
     * @return JsonResponse
     */
    public function listarEditorial(Request $request, InstitucionEditorialRepository $editorial)
    {
        try {
            $jsonParams = json_decode($request->getContent(), true);
//            $filtros['activo'] = true;
//            if (isset($jsonParams['activo'])) {
//                $filtros['activo'] = $jsonParams['activo'];
//            }
            $result = $editorial->getEditoriales();

            return $this->json($result);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), 403);
        }
    }
}
