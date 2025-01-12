<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\DocumentoSalida;
use App\Entity\Security\User;
use App\Entity\Tramite\DocumentoSalidaConceptoGasto;
use App\Entity\Tramite\DocumentoSalidaEstado;
use App\Entity\Tramite\DocumentoSalidaTramite;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Tramite\FichaSalidaEstado;
use App\Entity\Tramite\Pasaporte;
use App\Form\Tramite\AsignarFechaSalidaType;
use App\Form\Tramite\AsignarTramiteType;
use App\Form\Tramite\CambioEstadoSalidaType;
use App\Form\Tramite\ConceptoSalidaType;
use App\Form\Tramite\DocumentoSalidaType;
use App\Repository\Economia\ConceptoGastoRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Repository\Tramite\DocumentoSalidaRepository;
use App\Repository\Tramite\DocumentoSalidaTramiteRepository;
use App\Repository\Tramite\EstadoDocumentoSalidaRepository;
use App\Repository\Tramite\DocumentoSalidaConceptoGastoRepository;
use App\Repository\Tramite\DocumentoSalidaEstadoEstadoRepository;
use App\Repository\Tramite\DocumentoSalidaEstadoRepository;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
use App\Repository\Tramite\FichaSalidaEstadoRepository;
use App\Repository\Tramite\FichaSalidaRepository;
use App\Repository\Tramite\PasaporteRepository;
use App\Repository\Tramite\TramiteRepository;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tramite/documento_salida")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_DOCUMENTO_SALIDA")
 */
class DocumentoSalidaController extends AbstractController
{

    /**
     * @Route("/", name="app_documento_salida_index", methods={"GET", "POST"})
     * @param documentoSalidaRepository $documentoSalidaRepository
     * @return Response
     */
    public function index(Request $request, DocumentoSalidaRepository $documentoSalidaRepository, EntityManagerInterface $entityManager, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        $allPost = $request->request->all();

        if (isset($allPost['estado']) && !empty($allPost['estado'])) {
            $request->getSession()->set('documento_salida_fil_estado', $allPost['estado']);
            $request->getSession()->set('documento_salida_text_fil_estado', $estadoFichaSalidaRepository->find($allPost['estado'])->getNombre());
        }
        if (isset($allPost['estado']) && empty($allPost['estado'])) {
            $request->getSession()->remove('documento_salida_fil_estado');
            $request->getSession()->remove('documento_salida_text_fil_estado');
        }
        $filtros = [];
        if ($request->getSession()->has('documento_salida_fil_estado')) {
            $filtros['estadoDocumentoSalida'] = $request->getSession()->get('documento_salida_fil_estado');
        }
        $registros = $documentoSalidaRepository->findBy($filtros, ['id' => 'desc']);
        $currentDate = new \DateTime();

//        foreach ($registros as $value) {
//            if ($value->getFechaSalidaReal() >= $currentDate) {
//                $value->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find(10));
//            }
//            else {
//                $value->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find(5));
//            }
//            $entityManager->persist($value);
//        }
//        $entityManager->flush();
        $registros = $documentoSalidaRepository->findBy($filtros, ['id' => 'desc']);
        return $this->render('modules/tramite/documento_salida/index.html.twig', [
            'registros' => $registros,
            'estados' => $estadoFichaSalidaRepository->findBy(['activo' => true, 'documentoSalida' => true], ['nombre' => 'asc']),
            'fil_estado' => $request->getSession()->get('documento_salida_fil_estado'),
            'text_fil' => $request->getSession()->has('documento_salida_text_fil_estado') ? " (Estado=" . $request->getSession()->get('documento_salida_text_fil_estado') . ")" : null,
        ]);
    }

    /**
     * @Route("/{id}/asignar_tramite", name="app_documento_salida_asignar_tramite", methods={"GET", "POST"})
     * @param Request $request
     * @param DocumentoSalida $documentoSalida
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @param EstadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function asignarTramites(Request $request, PasaporteRepository $pasaporteRepository, TramiteRepository $tramiteRepository, DocumentoSalida $documentoSalida, DocumentoSalidaTramiteRepository $documentoSalidaTramiteRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository, DocumentoSalidaRepository $documentoSalidaRepository)
    {
        try {

            $form = $this->createForm(AsignarTramiteType::class, null, ['action' => 'modificar']);
            $form->handleRequest($request);
            $data = $documentoSalidaTramiteRepository->findBy(['documentoSalida' => $documentoSalida->getId()]);
            if ($form->isSubmitted() && $form->isValid()) {
                if (isset($_POST['asignar_tramite']['tramite'])) {
                    foreach ($data as $value) {
                        $documentoSalidaTramiteRepository->remove($value, true);
                    }
                    foreach ($_POST['asignar_tramite']['tramite'] as $value) {
                        $entidad = new DocumentoSalidaTramite();
                        $entidad->setDocumentoSalida($documentoSalida);
                        $entidad->setTramite($tramiteRepository->find($value));
                        $documentoSalidaTramiteRepository->add($entidad, true);
                        if ($this->getParameter('tramite_confeccion_pasaporte') == $value) {
                            //si no hay ningun pasaporte asignado a esta persona pendiente por crear
                            $existPasaporte = $pasaporteRepository->findBy(['persona' => $documentoSalida->getPersona(), 'activo' => 0]);
                            if (!$existPasaporte) {
                                $solicitudPasaporte = new Pasaporte();
                                $solicitudPasaporte->setPersona($documentoSalida->getPersona());
                                $solicitudPasaporte->setTipoPasaporte($documentoSalida->getTipoPasaporte());
                                $pasaporteRepository->add($solicitudPasaporte, true);
                            }
                        }
                    }
                }
                $documentoSalida->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_tramite')));
                $documentoSalidaRepository->edit($documentoSalida, true);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            $tramitesAsignados = [];
            foreach ($data as $value) {
                if (!empty($documentoSalida->getNumeroPasaporte())) {
                    if ($value->getTramite()->getId() == $this->getParameter('tramite_confeccion_pasaporte')) {
                        continue;
                    }
                }
                $tramitesAsignados[] = $value->getTramite()->getId();
            }

            return $this->render('modules/tramite/documento_salida/asignarTramite.html.twig', [
                'form' => $form->createView(),
                'documentoSalida' => $documentoSalida,
                'persona' => $documentoSalida->getPersona(),
                'tramitesAsociados' => json_encode($tramitesAsignados)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_asignar_tramite', ['id' => $documentoSalida->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/enviar_a_tramite", name="app_documento_salida_enviarA_tramite", methods={"GET"})
     * @param DocumentoSalida $documentoSalida
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @param EstadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function enviarATramites(DocumentoSalida $documentoSalida, DocumentoSalidaRepository $documentoSalidaRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            if ($documentoSalidaRepository->find($documentoSalida) instanceof DocumentoSalida) {
                $documentoSalida->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_tramite')));
                $documentoSalidaRepository->edit($documentoSalida, true);
                $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_fecha_salida_regreso", name="app_documento_salida_asignar_fecha_salida_regreso", methods={"GET", "POST"})
     * @param Request $request
     * @param DocumentoSalida $documentoSalida
     * @param EstadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @return Response
     */
    public function asignarFechaSalidaRegreso(Request $request, DocumentoSalida $documentoSalida, EstadoFichaSalidaRepository $estadoFichaSalidaRepository, DocumentoSalidaRepository $documentoSalidaRepository)
    {
        try {
            $form = $this->createForm(AsignarFechaSalidaType::class, $documentoSalida, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['asignar_fecha_salida']['fechaSalidaReal']);
                $documentoSalida->setFechaSalidaReal(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['asignar_fecha_salida']['fechaRegresoReal']);
                $documentoSalida->setFechaRegresoReal(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $documentoSalida->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_viajando')));
                $documentoSalidaRepository->edit($documentoSalida, true);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/documento_salida/asignarFechaSalidaRegreso.html.twig', [
                'form' => $form->createView(),
                'documentoSalida' => $documentoSalida,
                'persona' => $documentoSalida->getPersona(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/aprobar_tramites", name="app_documento_salida_aprobar_tramites", methods={"GET", "POST"})
     * @param DocumentoSalida $documentoSalida
     * @return Response
     */
    public function aprobarTramites(DocumentoSalida $documentoSalida, DocumentoSalidaTramiteRepository $documentoSalidaTramiteRepository)
    {
        try {
            $listo = true;
            $tramites = $documentoSalidaTramiteRepository->findBy(['documentoSalida' => $documentoSalida], ['id' => 'asc']);
            foreach ($tramites as $value) {
                if (!$value->getListo()) {
                    $listo = false;
                    break;
                }
            }
            return $this->render('modules/tramite/documento_salida/aprobarTramites.html.twig', [
                'tramites' => $tramites,
                'listo' => $listo,
                'documentoSalida' => $documentoSalida,
                'persona' => $documentoSalida->getPersona(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/guardar_estado_tramite", name="app_documento_salida_aprobar_tramites_guardar", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function guardarFiltro(Request $request, EntityManagerInterface $entityManager, DocumentoSalidaTramiteRepository $documentoSalidaTramiteRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            $allPost = $request->request->All();
            $registro = $documentoSalidaTramiteRepository->find($allPost['id']);
            $registro->setListo($allPost['valor']);
            $documentoSalidaTramiteRepository->edit($registro, true);

            $listo = true;
            $tramites = $documentoSalidaTramiteRepository->findBy(['documentoSalida' => $registro->getDocumentoSalida()->getId()], ['id' => 'asc']);
            foreach ($tramites as $value) {
                if (!$value->getListo()) {
                    $listo = false;
                    break;
                }
            }

            if ($listo) {
                $registro->getDocumentoSalida()->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_listo')));
            } else {
                $registro->getDocumentoSalida()->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_tramite')));
            }
            $entityManager->persist($registro->getDocumentoSalida());
            $entityManager->flush();

            //falta el cambio de estado del documento
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json(null);
        }
    }

    /**
     * @Route("/{id}/finalizar_salida", name="app_documento_salida_finalizar_salida", methods={"GET"})
     * @param DocumentoSalida $documentoSalida
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @param EstadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function finalizarSalida(DocumentoSalida $documentoSalida, DocumentoSalidaRepository $documentoSalidaRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            if ($documentoSalidaRepository->find($documentoSalida) instanceof DocumentoSalida) {
                $documentoSalida->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_finalizada')));
                $documentoSalida->setFechaFinalizado(new \DateTime());
                $documentoSalidaRepository->edit($documentoSalida, true);
                $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/viajando", name="app_documento_salida_viajando", methods={"GET"})
     * @param DocumentoSalida $documentoSalida
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @param EstadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function viajando(DocumentoSalida $documentoSalida, DocumentoSalidaRepository $documentoSalidaRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        try {
            if ($documentoSalidaRepository->find($documentoSalida) instanceof DocumentoSalida) {
                $documentoSalida->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find(10));
                $documentoSalida->setFechaSalidaReal(new \DateTime());
                $documentoSalidaRepository->edit($documentoSalida, true);
                $this->addFlash('success', 'El elemento ha sido modificado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/firmar", name="app_documento_salida_firmar", methods={"GET"})
     * @param Request $request
     * @param DocumentoSalida $documentoSalida
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @return Response
     */
    public function firmar(Request $request, DocumentoSalida $documentoSalida, DocumentoSalidaRepository $documentoSalidaRepository)
    {
        try {
            if ($documentoSalidaRepository->find($documentoSalida) instanceof DocumentoSalida) {
                $documentoSalida->setFechaFirmaDirectivo(new \DateTime());
                $documentoSalida->setDirectivoFirma($this->getUser());
                $documentoSalidaRepository->edit($documentoSalida, true);
                $this->addFlash('success', 'El elemento ha sido firmado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
