<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Pais;
use App\Form\Admin\PaisType;
use App\Repository\Estructura\PaisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/pais")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PROVIN")
 */
class PaisController extends AbstractController
{
    /**
     * @Route("/", name="app_pais_index", methods={"GET"})
     * @param PaisRepository $paisRepository
     * @return Response
     */
    public function index(PaisRepository $paisRepository): Response
    {
        try {
            return $this->render('modules/estructura/pais/index.html.twig', [
                'pais' => $paisRepository->findBy([], ['nombre' => 'asc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pais_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
