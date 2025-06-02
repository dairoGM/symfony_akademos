<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Entidad;
use App\Entity\Estructura\Estructura;
use App\Form\Estructura\EntidadType;
use App\Repository\Estructura\EntidadRepository;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Estructura\MunicipioRepository;
use App\Repository\Personal\PersonaRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/unidad_presupuestada/tratamiento_especial")
 * @IsGranted("ROLE_ADMIN", "ROLE_ESTRUCTURA_UNIDAD_PRESUPUESTADA_TRATAMIENTO_ESPECIAL")
 */
class UnidadPresupuestadaTratamientoEspecialController extends AbstractController
{


    /**
     * @Route("/", name="app_estructura_unidad_presupuestada_tratamiento_especial_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function index(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        try {
            $registros = $estructuraRepository->findBy(['esEntidad' => true, 'clasificacionPresupuestaria' => 'UPTE'], ['activo' => 'desc', 'id' => 'desc']);
            return $this->render('modules/estructura/estructura/unidad_presupuestada/upte.html.twig', [
                'registros' => $registros
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_unidad_presupuestada_tratamiento_especial_index', [], Response::HTTP_SEE_OTHER);
        }
    }


}
