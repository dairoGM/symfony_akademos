<?php

namespace App\Controller\Personal;

use App\Entity\Personal\ClasificacionPersona;
use App\Entity\Security\User;
use App\Form\Personal\ClasificacionPersonaType;
use App\Repository\Personal\ClasificacionPersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/clasificacion_persona")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CLAPER")
 */
class ClasificacionPersonaController extends AbstractController
{

    /**
     * @Route("/", name="app_clasificacion_persona_index", methods={"GET"})
     * @param ClasificacionPersonaRepository $ClasificacionPersonaRepository
     * @return Response
     */
    public function index(ClasificacionPersonaRepository $ClasificacionPersonaRepository)
    {

        return $this->render('modules/personal/clasificacion_persona/index.html.twig', [
            'registros' => $ClasificacionPersonaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);

    }

    /**
     * @Route("/registrar", name="app_clasificacion_persona_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ClasificacionPersonaRepository $ClasificacionPersonaRepository
     * @return Response
     */
    public function registrar(Request $request, ClasificacionPersonaRepository $ClasificacionPersonaRepository)
    {
        try {
            $catDocenteEntity = new ClasificacionPersona();
            $form = $this->createForm(ClasificacionPersonaType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $ClasificacionPersonaRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_clasificacion_persona_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/clasificacion_persona/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_clasificacion_persona_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_clasificacion_persona_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $ClasificacionPersona
     * @param ClasificacionPersonaRepository $ClasificacionPersonaRepository
     * @return Response
     */
    public function modificar(Request $request, ClasificacionPersona $ClasificacionPersona, ClasificacionPersonaRepository $ClasificacionPersonaRepository)
    {
        try {
            $form = $this->createForm(ClasificacionPersonaType::class, $ClasificacionPersona, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ClasificacionPersonaRepository->edit($ClasificacionPersona);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_clasificacion_persona_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/personal/clasificacion_persona/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_clasificacion_persona_modificar', ['id' => $ClasificacionPersona], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_clasificacion_persona_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $ClasificacionPersona
     * @param ClasificacionPersonaRepository $ClasificacionPersonaRepository
     * @return Response
     */
    public function detail(Request $request, ClasificacionPersona $ClasificacionPersona)
    {
        return $this->render('modules/personal/clasificacion_persona/detail.html.twig', [
            'item' => $ClasificacionPersona,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_clasificacion_persona_eliminar", methods={"GET"})
     * @param Request $request
     * @param ClasificacionPersona $ClasificacionPersona
     * @param ClasificacionPersonaRepository $ClasificacionPersonaRepository
     * @return Response
     */
    public function eliminar(Request $request, ClasificacionPersona $ClasificacionPersona, ClasificacionPersonaRepository $ClasificacionPersonaRepository)
    {
        try {
            if ($ClasificacionPersonaRepository->find($ClasificacionPersona) instanceof ClasificacionPersona) {
                $ClasificacionPersonaRepository->remove($ClasificacionPersona, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_clasificacion_persona_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_clasificacion_persona_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_clasificacion_persona_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
