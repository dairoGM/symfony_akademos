<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\AE2;
use App\Entity\RRHH\CategoriaDocente;
use App\Entity\Security\User;
use App\Form\RRHH\AE2Type;
use App\Form\RRHH\CategoriaDocenteType;
use App\Repository\RRHH\AE2Repository;
use App\Repository\RRHH\CategoriaDocenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/rrhh/ae3")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE3")
 */
class AE3Controller extends AbstractController
{

    /**
     * @Route("/", name="app_rrhh_reporte_ae3_index", methods={"GET"})
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function index(CategoriaDocenteRepository $categoriaDocenteRepository)
    {
        return $this->render('modules/rrhh/reportes/ae3/index.html.twig', [
            'registros' => $categoriaDocenteRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

}
