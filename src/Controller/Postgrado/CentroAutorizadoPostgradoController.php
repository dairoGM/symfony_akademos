<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Postgrado\SolicitudProgramaComision;
use App\Entity\Security\User;
use App\Form\Estructura\EntidadType;
use App\Form\Postgrado\AprobarProgramaType;
use App\Form\Postgrado\CambioEstadoProgramaType;
use App\Form\Postgrado\ComisionProgramaType;
use App\Form\Postgrado\EntidaCentroAutorizadoType;
use App\Form\Postgrado\NoAprobarProgramaType;
use App\Form\Postgrado\SolicitudProgramaType;
use App\Repository\Estructura\EntidadRepository;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Estructura\MunicipioRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\ComisionRepository;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\SolicitudProgramaComisionRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Services\TraceService;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/centro_autorizado")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CENTRO_AUTORIZADO")
 */
class CentroAutorizadoPostgradoController extends AbstractController
{

    /**
     * @Route("/index", name="app_postgrado_centro_autorizado_postgrado_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function indexEstructurasCAP(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        try {
            $registros = $estructuraRepository->findBy(['centroAutorizadoPosgrado' => 1], ['activo' => 'desc', 'id' => 'desc']);
            return $this->render('modules/estructura/estructura/centroAutorizadoPosgrado.html.twig', [
                'registros' => $registros
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_postgrado_centro_autorizado_postgrado_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/registrar", name="app_postgrado_centro_autorizado_postgrado_registrar", methods={"GET", "POST"})
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

        $form = $this->createForm(EntidaCentroAutorizadoType::class, null, ['data_choices' => -1, 'estructuraNegocio' => $estructuraNegocio]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $estructura = $estructuraRepository->find($form->get('estructura')->getData()->getId());
            $estructura->setCentroAutorizadoPosgrado(true);
            $estructuraRepository->edit($estructura, true);
            $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
            return $this->redirectToRoute('app_postgrado_centro_autorizado_postgrado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modules/postgrado/centro_autorizado/new.html.twig', [
            'form' => $form->createView(),
            'entidad' => $entidad
        ]);

    }
}
