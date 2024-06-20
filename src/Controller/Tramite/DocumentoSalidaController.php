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
use App\Repository\Tramite\TramiteRepository;
use App\Services\Utils;
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
    public function index(Request $request, DocumentoSalidaRepository $documentoSalidaRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
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
            $filtros['estadoFichaSalida'] = $request->getSession()->get('documento_salida_fil_estado');
        }
        return $this->render('modules/tramite/documento_salida/index.html.twig', [
            'registros' => $documentoSalidaRepository->findBy($filtros, ['id' => 'desc']),
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
    public function asignarTramites(Request $request, TramiteRepository $tramiteRepository, DocumentoSalida $documentoSalida, DocumentoSalidaTramiteRepository $documentoSalidaTramiteRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository, DocumentoSalidaRepository $documentoSalidaRepository)
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
                    }
                }
                $documentoSalida->setEstadoDocumentoSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_firmado_directivo')));
                $documentoSalidaRepository->edit($documentoSalida, true);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_documento_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            $tramitesAsignados = [];
            foreach ($data as $value) {
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
     * @param DocumentoSalidaRepository $documentoSalidaRepository
     * @param EstadoFichaSalidaRepository $estadoFichaSalidaRepository
     * @return Response
     */
    public function asignarFechaSalidaRegreso(Request $request, TramiteRepository $tramiteRepository, DocumentoSalida $documentoSalida, DocumentoSalidaTramiteRepository $documentoSalidaTramiteRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository, DocumentoSalidaRepository $documentoSalidaRepository)
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
}
