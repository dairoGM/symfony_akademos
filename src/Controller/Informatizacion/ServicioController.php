<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\Servicio;
use App\Form\Informatizacion\ServicioType;
use App\Repository\Informatizacion\ServicioRepository;
use App\Repository\Personal\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/servicio")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_SERVICIO")
 */
class ServicioController extends AbstractController
{

    /**
     * @Route("/", name="app_servicio_index", methods={"GET"})
     * @param ServicioRepository $ServicioRepository
     * @return Response
     */
    public function index(ServicioRepository $ServicioRepository)
    {
        return $this->render('modules/informatizacion/servicio/index.html.twig', [
            'registros' => $ServicioRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_servicio_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ServicioRepository $servicioRepository
     * @return Response
     */
    public function registrar(Request $request, PersonaRepository $personaRepository, ServicioRepository $servicioRepository)
    {
        try {
            $entidad = new Servicio();
            $form = $this->createForm(ServicioType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                $entidad->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                $servicioRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_servicio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/servicio/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_servicio_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_servicio_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Servicio $servicio
     * @param ServicioRepository $servicioRepository
     * @return Response
     */
    public function modificar(Request $request, PersonaRepository $personaRepository, Servicio $servicio, ServicioRepository $servicioRepository)
    {
        try {
            $form = $this->createForm(ServicioType::class, $servicio, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!method_exists($servicio->getEstructura(), 'getId')) {
                    $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                    $servicio->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                }
                $servicioRepository->edit($servicio);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_servicio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/servicio/edit.html.twig', [
                'form' => $form->createView(),
                'servicio' => $servicio
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_servicio_modificar', ['id' => $servicio->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_servicio_detail", methods={"GET", "POST"})
     * @param servicio $servicio
     * @return Response
     */
    public function detail(Servicio $servicio)
    {
        return $this->render('modules/informatizacion/servicio/detail.html.twig', [
            'item' => $servicio,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_servicio_eliminar", methods={"GET"})
     * @param Servicio $servicio
     * @param ServicioRepository $servicioRepository
     * @return Response
     */
    public function eliminar(Servicio $servicio, ServicioRepository $servicioRepository)
    {
        try {
            if ($servicioRepository->find($servicio) instanceof Servicio) {
                $servicioRepository->remove($servicio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_servicio_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_servicio_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_servicio_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
