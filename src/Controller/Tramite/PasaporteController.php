<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Tramite\FichaSalidaConceptoGasto;
use App\Entity\Tramite\FichaSalidaEstado;
use App\Entity\Tramite\Pasaporte;
use App\Entity\Security\User;
use App\Form\Tramite\FichaSalidaType;
use App\Form\Tramite\PasaporteType;
use App\Form\Tramite\TramiteType;
use App\Repository\Economia\ConceptoGastoRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
use App\Repository\Tramite\FichaSalidaConceptoGastoRepository;
use App\Repository\Tramite\FichaSalidaEstadoRepository;
use App\Repository\Tramite\FichaSalidaRepository;
use App\Repository\Tramite\InstitucionExtranjeraRepository;
use App\Repository\Tramite\PasaporteRepository;
use App\Repository\Tramite\TramiteRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/pasaporte")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PASAPORTE")
 */
class PasaporteController extends AbstractController
{

    /**
     * @Route("/", name="app_pasaporte_index", methods={"GET"})
     * @param pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function index(PasaporteRepository $pasaporteRepository)
    {
        return $this->render('modules/tramite/pasaporte/index.html.twig', [
            'registros' => $pasaporteRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_pasaporte_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param ResponsableRepository $responsableRepository
     * @param Utils $utils
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
            return $this->render('modules/tramite/pasaporte/new.html.twig', [
                'registros' => $registros,
                'busqueda' => $allPost['busqueda'] ?? null
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/registrar-v2", name="app_pasaporte_registrar_v2", methods={"GET", "POST"})
     * @param Request $request
     * @param Persona $persona
     * @return Response
     */
    public function registrarV2(Request $request, Persona $persona, PasaporteRepository $pasaporteRepository, FichaSalidaRepository $fichaSalidaRepository)
    {
        try {
            $pasaporte = new Pasaporte();
            $form = $this->createForm(PasaporteType::class, $pasaporte, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pasaporte->setPersona($persona);
                if (!empty($request->request->all()['pasaporte']['fechaEmisionPasaporte'])) {
                    $pasaporte->setFechaEmisionPasaporte(\DateTime::createFromFormat('d/m/Y', $request->request->all()['pasaporte']['fechaEmisionPasaporte']));
                }
                if (!empty($request->request->all()['pasaporte']['fechaCaducidadPasaporte'])) {
                    $pasaporte->setFechaCaducidadPasaporte(\DateTime::createFromFormat('d/m/Y', $request->request->all()['pasaporte']['fechaCaducidadPasaporte']));
                }

                $pasaporteRepository->add($pasaporte, true);

                /*Actualizo todas las fichas que esten a la espera del pasaporte*/
                $fichasDependientes = $fichaSalidaRepository->findBy(['persona' => $pasaporte->getPersona()->getId(), 'estadoFichaSalida' => $this->getParameter('estado_salida_tramite')]);
                if (is_array($fichasDependientes)) {
                    foreach ($fichasDependientes as $fichasDependiente) {
                        $fichasDependiente->setNumeroPasaporte($pasaporte->setNumeroPasaporte());
                        $fichasDependiente->setTipoPasaporte($pasaporte->getTipoPasaporte());
                        $fichasDependiente->setFechaEmisionPasaporte($pasaporte->getFechaEmisionPasaporte());
                        $fichasDependiente->setFechaCaducidadPasaporte($pasaporte->getFechaCaducidadPasaporte());
                        $fichaSalidaRepository->edit($fichasDependiente, true);
                    }
                }

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/pasaporte/newV2.html.twig', [
                'form' => $form->createView(),
                'persona' => $persona
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_pasaporte_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Pasaporte $pasaporte
     * @param pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function modificar(Request $request, Pasaporte $pasaporte, PasaporteRepository $pasaporteRepository, FichaSalidaRepository $fichaSalidaRepository)
    {
        try {
            $form = $this->createForm(PasaporteType::class, $pasaporte, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $temp = explode('/', $request->request->all()['pasaporte']['fechaEmisionPasaporte']);
                $pasaporte->setFechaEmisionPasaporte(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['pasaporte']['fechaCaducidadPasaporte']);
                $pasaporte->setFechaCaducidadPasaporte(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $pasaporteRepository->edit($pasaporte);
                /*Actualizo todas las fichas que esten a la espera del pasaporte*/
                $fichasDependientes = $fichaSalidaRepository->findBy(['persona' => $pasaporte->getPersona()->getId(), 'estadoFichaSalida' => $this->getParameter('estado_salida_tramite')]);
                if (is_array($fichasDependientes)) {
                    foreach ($fichasDependientes as $fichasDependiente) {
                        $fichasDependiente->setNumeroPasaporte($pasaporte->setNumeroPasaporte());
                        $fichasDependiente->setTipoPasaporte($pasaporte->getTipoPasaporte());
                        $fichasDependiente->setFechaEmisionPasaporte($pasaporte->getFechaEmisionPasaporte());
                        $fichasDependiente->setFechaCaducidadPasaporte($pasaporte->getFechaCaducidadPasaporte());
                        $fichaSalidaRepository->edit($fichasDependiente, true);
                    }
                }

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/pasaporte/edit.html.twig', [
                'form' => $form->createView(),
                'pasaporte' => $pasaporte
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_modificar', ['id' => $pasaporte], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_pasaporte_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param pasaporte $pasaporte
     * @return Response
     */
    public function detail(Request $request, Pasaporte $pasaporte)
    {
        return $this->render('modules/tramite/pasaporte/detail.html.twig', [
            'item' => $pasaporte,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_pasaporte_eliminar", methods={"GET"})
     * @param Request $request
     * @param Pasaporte $pasaporte $pasaporte
     * @param PasaporteRepository $pasaporteRepository $pasaporteRepository
     * @return Response
     */
    public function eliminar(Request $request, Pasaporte $pasaporte, PasaporteRepository $pasaporteRepository)
    {
        try {
            if ($pasaporteRepository->find($pasaporte) instanceof Pasaporte) {
                $pasaporteRepository->remove($pasaporte, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_pasaporte_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * Add package entity.
     *
     * @Route("/{tipo}/{idPersona}/get_pasaporte_dado_tipo", name="app_pasaporte_get_pasaporte_dado_tipo", methods={"GET"})
     * @param Request $request
     * @param $tipo
     * @param $idPersona
     * @param PasaporteRepository $pasaporteRepository
     * @return JsonResponse
     */
    public function getPasaporteDadoTipo(Request $request, $tipo, $idPersona, PasaporteRepository $pasaporteRepository): JsonResponse
    {
        try {
            $datos = $pasaporteRepository->findBy(['persona' => $idPersona, 'tipoPasaporte' => $tipo, 'activo' => 1]);
            return $this->json($datos[0] ?? []);
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }
}
