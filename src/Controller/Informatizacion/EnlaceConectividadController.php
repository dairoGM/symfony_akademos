<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\EnlaceConectividad;
use App\Form\Informatizacion\EnlaceConectividadType;
use App\Repository\Informatizacion\EnlaceConectividadRepository;
use App\Repository\Personal\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/enlace_conectividad")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ENLACE_CONECTIVIDAD")
 */
class EnlaceConectividadController extends AbstractController
{

    /**
     * @Route("/", name="app_enlace_conectividad_index", methods={"GET"})
     * @param EnlaceConectividadRepository $EnlaceConectividadRepository
     * @return Response
     */
    public function index(EnlaceConectividadRepository $EnlaceConectividadRepository)
    {
        return $this->render('modules/informatizacion/enlaceConectividad/index.html.twig', [
            'registros' => $EnlaceConectividadRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_enlace_conectividad_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EnlaceConectividadRepository $enlaceConectividadRepository
     * @return Response
     */
    public function registrar(Request $request, PersonaRepository $personaRepository, EnlaceConectividadRepository $enlaceConectividadRepository)
    {
        try {
            $entidad = new EnlaceConectividad();
            $form = $this->createForm(EnlaceConectividadType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                $entidad->setEstructura($personaAutenticada->getEstructura()->getEstructura());

                $entidad->setNombre($entidad->getEd());
                $enlaceConectividadRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_enlace_conectividad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/enlaceConectividad/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_enlace_conectividad_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_enlace_conectividad_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param EnlaceConectividad $enlaceConectividad
     * @param EnlaceConectividadRepository $enlaceConectividadRepository
     * @return Response
     */
    public function modificar(Request $request, PersonaRepository $personaRepository, EnlaceConectividad $enlaceConectividad, EnlaceConectividadRepository $enlaceConectividadRepository)
    {
        try {
            $form = $this->createForm(EnlaceConectividadType::class, $enlaceConectividad, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!method_exists($enlaceConectividad->getEstructura(), 'getId')) {
                    $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                    $enlaceConectividad->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                }
                $enlaceConectividadRepository->edit($enlaceConectividad);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_enlace_conectividad_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/enlaceConectividad/edit.html.twig', [
                'form' => $form->createView(),
                'enlaceConectividad' => $enlaceConectividad
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_enlace_conectividad_modificar', ['id' => $enlaceConectividad->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_enlace_conectividad_detail", methods={"GET", "POST"})
     * @param enlaceConectividad $enlaceConectividad
     * @return Response
     */
    public function detail(EnlaceConectividad $enlaceConectividad)
    {
        return $this->render('modules/informatizacion/enlaceConectividad/detail.html.twig', [
            'item' => $enlaceConectividad,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_enlace_conectividad_eliminar", methods={"GET"})
     * @param EnlaceConectividad $enlaceConectividad
     * @param EnlaceConectividadRepository $enlaceConectividadRepository
     * @return Response
     */
    public function eliminar(EnlaceConectividad $enlaceConectividad, EnlaceConectividadRepository $enlaceConectividadRepository)
    {
        try {
            if ($enlaceConectividadRepository->find($enlaceConectividad) instanceof EnlaceConectividad) {
                $enlaceConectividadRepository->remove($enlaceConectividad, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_enlace_conectividad_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_enlace_conectividad_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_enlace_conectividad_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
