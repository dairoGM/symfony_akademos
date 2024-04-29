<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Security\User;
use App\Form\Tramite\ConceptoSalidaType;
use App\Form\Tramite\FichaSalidaType;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Repository\Tramite\FichaSalidaRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/ficha_salida")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_FICHA_SALIDA")
 */
class FichaSalidaController extends AbstractController
{

    /**
     * @Route("/", name="app_ficha_salida_index", methods={"GET"})
     * @param fichaSalidaRepository $fichaSalidaRepository
     * @return Response
     */
    public function index(FichaSalidaRepository $fichaSalidaRepository)
    {
        return $this->render('modules/tramite/ficha_salida/index.html.twig', [
            'registros' => $fichaSalidaRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_ficha_salida_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param fichaSalidaRepository $fichaSalidaRepository
     * @return Response
     */
    public function registrar(Request $request, PersonaRepository $personaRepository, ResponsableRepository $responsableRepository, Utils $utils)
    {
        try {
            $allPost = $request->request->all();
            $registros = [];

            if (isset($allPost['busqueda']) && !empty($allPost['busqueda'])) {
                $registros = $personaRepository->findBy(['carnetIdentidad' => $allPost['busqueda']]);
            }
            return $this->render('modules/tramite/ficha_salida/new.html.twig', [
                'registros' => $registros,
                'busqueda' => $allPost['busqueda'] ?? null
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_ficha_salida_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/registrar-v2", name="app_ficha_salida_registrar_v2", methods={"GET", "POST"})
     * @param Request $request
     * @param Persona $persona
     * @return Response
     */
    public function registrarV2(Request $request, Persona $persona, FichaSalidaRepository $fichaSalidaRepository)
    {
        try {
            $entidad = new FichaSalida();
            $form = $this->createForm(FichaSalidaType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entidad->setPersona($persona);

                $entidad->setFechaSalidaPrevista(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaSalidaPrevista']));
                $entidad->setFechaSalidaReal(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaSalidaReal']));
                $entidad->setFechaRegresoPrevista(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaRegresoPrevista']));
                $entidad->setFechaRegresoReal(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaRegresoReal']));
                $entidad->setFechaEmisionPasaporte(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaEmisionPasaporte']));
                $entidad->setFechaCaducidadPasaporte(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaCaducidadPasaporte']));

                $fichaSalidaRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/ficha_salida/newV2.html.twig', [
                'form' => $form->createView(),
                'persona' => $persona
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_ficha_salida_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_ficha_salida_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $fichaSalida
     * @param fichaSalidaRepository $fichaSalidaRepository
     * @return Response
     */
    public function modificar(Request $request, FichaSalida $fichaSalida, FichaSalidaRepository $fichaSalidaRepository)
    {
        try {
            $form = $this->createForm(FichaSalidaType::class, $fichaSalida, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['ficha_salida']['fechaSalidaPrevista']);
                $fichaSalida->setFechaSalidaPrevista(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaSalidaReal']);
                $fichaSalida->setFechaSalidaReal(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaRegresoPrevista']);
                $fichaSalida->setFechaRegresoPrevista(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaRegresoReal']);
                $fichaSalida->setFechaRegresoReal(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaEmisionPasaporte']);
                $fichaSalida->setFechaEmisionPasaporte(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaCaducidadPasaporte']);
                $fichaSalida->setFechaCaducidadPasaporte(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));


                $fichaSalidaRepository->edit($fichaSalida);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/ficha_salida/edit.html.twig', [
                'form' => $form->createView(),
                'fichaSalida' => $fichaSalida,
                'persona' => $fichaSalida->getPersona()
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_ficha_salida_modificar', ['id' => $fichaSalida], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_ficha_salida_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param fichaSalida $fichaSalida
     * @return Response
     */
    public function detail(Request $request, FichaSalida $fichaSalida)
    {
        return $this->render('modules/tramite/ficha_salida/detail.html.twig', [
            'item' => $fichaSalida,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_ficha_salida_eliminar", methods={"GET"})
     * @param Request $request
     * @param fichaSalida $fichaSalida
     * @param fichaSalidaRepository $fichaSalidaRepository
     * @return Response
     */
    public function eliminar(Request $request, FichaSalida $fichaSalida, FichaSalidaRepository $fichaSalidaRepository)
    {
        try {
            if ($fichaSalidaRepository->find($fichaSalida) instanceof FichaSalida) {
                $fichaSalidaRepository->remove($fichaSalida, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
