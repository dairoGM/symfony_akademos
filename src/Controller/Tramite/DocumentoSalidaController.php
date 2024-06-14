<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\DocumentoSalida;
use App\Entity\Security\User;
use App\Entity\Tramite\DocumentoSalidaConceptoGasto;
use App\Entity\Tramite\DocumentoSalidaEstado;
use App\Form\Tramite\CambioEstadoSalidaType;
use App\Form\Tramite\ConceptoSalidaType;
use App\Form\Tramite\DocumentoSalidaType;
use App\Repository\Economia\ConceptoGastoRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Repository\Tramite\DocumentoSalidaRepository;
use App\Repository\Tramite\EstadoDocumentoSalidaRepository;
use App\Repository\Tramite\DocumentoSalidaConceptoGastoRepository;
use App\Repository\Tramite\DocumentoSalidaEstadoEstadoRepository;
use App\Repository\Tramite\DocumentoSalidaEstadoRepository;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
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

}
