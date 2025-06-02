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
 * @Route("/estructura/empresas")
 * @IsGranted("ROLE_ADMIN", "ROLE_ESTRUCTURA_EMPRESA")
 */
class EmpresaController extends AbstractController
{


    /**
     * @Route("/", name="app_estructura_empresa_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function index(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        try {
            $registros = $estructuraRepository->findBy(['categoriaEstructura' => 7], ['activo' => 'desc', 'id' => 'desc']);
            return $this->render('modules/estructura/estructura/empresa.html.twig', [
                'registros' => $registros
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_empresa_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_estructura_empresa_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EntidadRepository $entidadRepository
     * @param MunicipioRepository $municipioRepository
     * @return Response
     */
    public function registrar(Request $request, PersonaRepository $personaRepository, EstructuraRepository $estructuraRepository, EntidadRepository $entidadRepository, MunicipioRepository $municipioRepository)
    {
        $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
        $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
        $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
        $estructuraNegocio = [$entidad->getId()];
        if ($isAdmin) {
            $estructuraNegocio = [];
        }

        $form = $this->createForm(EntidadType::class, null, ['data_choices' => -1, 'estructuraNegocio' => $estructuraNegocio]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $estructura = $estructuraRepository->find($form->get('estructura')->getData()->getId());
            $estructura->setEsEntidad(true);
            $estructuraRepository->edit($estructura, true);
            $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
            return $this->redirectToRoute('app_estructura_empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modules/estructura/entidad/new.html.twig', [
            'form' => $form->createView(),
            'entidad' => $entidad
        ]);

    }


}
