<?php

namespace App\Controller\Informatizacion;


use App\Entity\Informatizacion\NautaHogar;
use App\Entity\Personal\Persona;
use App\Form\Informatizacion\NautaHogarType;
use App\Repository\Informatizacion\NautaHogarRepository;
use App\Repository\Personal\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/nauta_hogar")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_NAUTA_HOGAR")
 */
class NautaHogarController extends AbstractController
{
    /**
     * @Route("/", name="app_nauta_hogar_index", methods={"GET", "POST"})
     * @param nautaHogarRepository $nautaHogarRepository
     * @return Response
     */
    public function index(Request $request, NautaHogarRepository $nautaHogarRepository)
    {
        return $this->render('modules/informatizacion/nautaHogar/index.html.twig', [
            'registros' => $nautaHogarRepository->findBy([], ['id' => 'desc'])

        ]);
    }

    /**
     * @Route("/registrar", name="app_nauta_hogar_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function registrar(Request $request, PersonaRepository $personaRepository)
    {
        try {
            $allPost = $request->request->all();
            $registros = [];

            if (isset($allPost['busqueda']) && !empty($allPost['busqueda'])) {
                $registros = $personaRepository->findBy(['carnetIdentidad' => $allPost['busqueda']]);
            }
            return $this->render('modules/informatizacion/nautaHogar/new.html.twig', [
                'registros' => $registros,
                'busqueda' => $allPost['busqueda'] ?? null
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nauta_hogar_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/registrar-v2", name="app_nauta_hogar_registrar_v2", methods={"GET", "POST"})
     * @param Request $request
     * @param Persona $persona
     * @return Response
     */
    public function registrarV2(Request $request, PersonaRepository $personaRepository, Persona $persona, NautaHogarRepository $nautaHogarRepository)
    {
        try {
            $entidad = new NautaHogar();
            $form = $this->createForm(NautaHogarType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entidad->setResponsable($persona);
                $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                $entidad->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                $nautaHogarRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_nauta_hogar_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/nautaHogar/newV2.html.twig', [
                'form' => $form->createView(),
                'persona' => $persona
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nauta_hogar_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_nauta_hogar_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param NautaHogar $nautaHogar
     * @param nautaHogarRepository $nautaHogarRepository
     * @return Response
     */
    public function modificar(Request $request, PersonaRepository $personaRepository, NautaHogar $nautaHogar, NautaHogarRepository $nautaHogarRepository)
    {
        try {
            $form = $this->createForm(NautaHogarType::class, $nautaHogar, ['action' => 'modificar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!method_exists($nautaHogar->getEstructura(), 'getId')) {
                    $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                    $nautaHogar->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                }
                $nautaHogarRepository->edit($nautaHogar);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_nauta_hogar_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/nautaHogar/edit.html.twig', [
                'form' => $form->createView(),
                'nautaHogar' => $nautaHogar,
                'persona' => $nautaHogar->getResponsable()
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nauta_hogar_modificar', ['id' => $nautaHogar->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_nauta_hogar_detail", methods={"GET", "POST"})
     * @param nautaHogar $nautaHogar
     * @return Response
     */
    public function detail(NautaHogar $nautaHogar)
    {
        return $this->render('modules/informatizacion/nautaHogar/detail.html.twig', [
            'item' => $nautaHogar,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_nauta_hogar_eliminar", methods={"GET"})
     * @param nautaHogar $nautaHogar
     * @param nautaHogarRepository $nautaHogarRepository
     * @return Response
     */
    public function eliminar(NautaHogar $nautaHogar, NautaHogarRepository $nautaHogarRepository)
    {
        try {
            if ($nautaHogarRepository->find($nautaHogar) instanceof NautaHogar) {
                $nautaHogarRepository->remove($nautaHogar, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_nauta_hogar_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_nauta_hogar_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_nauta_hogar_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
