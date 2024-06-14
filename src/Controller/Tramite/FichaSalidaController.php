<?php

namespace App\Controller\Tramite;

use App\Entity\Personal\Persona;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\DocumentoSalida;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Security\User;
use App\Entity\Tramite\FichaSalidaConceptoGasto;
use App\Entity\Tramite\FichaSalidaEstado;
use App\Form\Tramite\CambioEstadoSalidaType;
use App\Form\Tramite\ConceptoSalidaType;
use App\Form\Tramite\FichaSalidaType;
use App\Repository\Economia\ConceptoGastoRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Repository\Tramite\DocumentoSalidaRepository;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
use App\Repository\Tramite\FichaSalidaConceptoGastoRepository;
use App\Repository\Tramite\FichaSalidaEstadoEstadoRepository;
use App\Repository\Tramite\FichaSalidaEstadoRepository;
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
     * @Route("/", name="app_ficha_salida_index", methods={"GET", "POST"})
     * @param fichaSalidaRepository $fichaSalidaRepository
     * @return Response
     */
    public function index(Request $request, FichaSalidaRepository $fichaSalidaRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository)
    {
        $allPost = $request->request->all();

        if (isset($allPost['estado']) && !empty($allPost['estado'])) {
            $request->getSession()->set('ficha_salida_fil_estado', $allPost['estado']);
            $request->getSession()->set('ficha_salida_text_fil_estado', $estadoFichaSalidaRepository->find($allPost['estado'])->getNombre());
        }
        if (isset($allPost['estado']) && empty($allPost['estado'])) {
            $request->getSession()->remove('ficha_salida_fil_estado');
            $request->getSession()->remove('ficha_salida_text_fil_estado');
        }
        $filtros = [];
        if ($request->getSession()->has('ficha_salida_fil_estado')) {
            $filtros['estadoFichaSalida'] = $request->getSession()->get('ficha_salida_fil_estado');
        }
        return $this->render('modules/tramite/ficha_salida/index.html.twig', [
            'registros' => $fichaSalidaRepository->findBy($filtros, ['id' => 'desc']),
            'estados' => $estadoFichaSalidaRepository->findBy(['activo' => true, 'documentoSalida' => false], ['nombre' => 'asc']),
            'fil_estado' => $request->getSession()->get('ficha_salida_fil_estado'),
            'text_fil' => $request->getSession()->has('ficha_salida_text_fil_estado') ? " (Estado=" . $request->getSession()->get('ficha_salida_text_fil_estado') . ")" : null,
        ]);
    }

    /**
     * @Route("/registrar", name="app_ficha_salida_registrar", methods={"GET", "POST"})
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
    public function registrarV2(Request $request, Persona $persona, ConceptoGastoRepository $conceptoGastoRepository, FichaSalidaConceptoGastoRepository $fichaSalidaConceptoGastoRepository, FichaSalidaRepository $fichaSalidaRepository, EstadoFichaSalidaRepository $estadoFichaSalidaRepository, FichaSalidaEstadoRepository $fichaSalidaEstadoRepository)
    {
        try {
            $entidad = new FichaSalida();
            $form = $this->createForm(FichaSalidaType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $allPost = $request->request->all();
                $entidad->setPersona($persona);

                if (!empty($request->request->all()['ficha_salida']['fechaSalidaPrevista'])) {
                    $entidad->setFechaSalidaPrevista(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaSalidaPrevista']));
                }
                if (!empty($request->request->all()['ficha_salida']['fechaSalidaReal'])) {
                    $entidad->setFechaSalidaReal(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaSalidaReal']));
                }
                if (!empty($request->request->all()['ficha_salida']['fechaRegresoPrevista'])) {
                    $entidad->setFechaRegresoPrevista(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaRegresoPrevista']));
                }
                if (!empty($request->request->all()['ficha_salida']['fechaRegresoReal'])) {
                    $entidad->setFechaRegresoReal(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaRegresoReal']));
                }
                if (!empty($request->request->all()['ficha_salida']['fechaEmisionPasaporte'])) {
                    $entidad->setFechaEmisionPasaporte(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaEmisionPasaporte']));
                }
                if (!empty($request->request->all()['ficha_salida']['fechaCaducidadPasaporte'])) {
                    $entidad->setFechaCaducidadPasaporte(\DateTime::createFromFormat('d/m/Y', $request->request->all()['ficha_salida']['fechaCaducidadPasaporte']));
                }
                $estadoFicha = $estadoFichaSalidaRepository->find($this->getParameter('estado_salida_creada'));
                $entidad->setEstadoFichaSalida($estadoFicha);

                if (!empty($_FILES['ficha_salida']['name']['cartaInvitacion'])) {
                    $file = $form['cartaInvitacion']->getData();
                    $file_name = $_FILES['ficha_salida']['name']['cartaInvitacion'];
                    $entidad->setCartaInvitacion($file_name);
                    $file->move("uploads/tramites/ficha_salida/carta_invitacion", $file_name);
                }

                $fichaSalidaRepository->add($entidad, true);

                $fichaSalidaEstado = new FichaSalidaEstado();
                $fichaSalidaEstado->setFichaSalida($entidad);
                $fichaSalidaEstado->setEstadoFichaSalida($estadoFicha);
                $fichaSalidaEstado->setDescripcion('Creada');
                $fichaSalidaEstadoRepository->add($fichaSalidaEstado, true);

                foreach ($allPost['ficha_salida']['conceptoGasto'] as $value) {
                    $conceptoGasto = new FichaSalidaConceptoGasto();
                    $conceptoGasto->setFichaSalida($entidad);
                    $conceptoGasto->setConceptoGasto($conceptoGastoRepository->find($value));
                    $fichaSalidaConceptoGastoRepository->add($conceptoGasto, true);
                }

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
     * @param FichaSalida $fichaSalida
     * @param fichaSalidaRepository $fichaSalidaRepository
     * @param ConceptoGastoRepository $conceptoGastoRepository
     * @param FichaSalidaConceptoGastoRepository $fichaSalidaConceptoGastoRepository
     * @return Response
     */
    public function modificar(Request $request, FichaSalida $fichaSalida, FichaSalidaRepository $fichaSalidaRepository, ConceptoGastoRepository $conceptoGastoRepository, FichaSalidaConceptoGastoRepository $fichaSalidaConceptoGastoRepository)
    {
        try {
            $form = $this->createForm(FichaSalidaType::class, $fichaSalida, ['action' => 'modificar']);
            $form->handleRequest($request);
            $allPost = $request->request->all();
            $conceptoGastosAsociados = $fichaSalidaConceptoGastoRepository->findBy(['fichaSalida' => $fichaSalida->getId()]);

            if ($form->isSubmitted()/* && $form->isValid()*/) {
                $temp = explode('/', $request->request->all()['ficha_salida']['fechaSalidaPrevista']);
                $fichaSalida->setFechaSalidaPrevista(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

//                $temp = explode('/', $request->request->all()['ficha_salida']['fechaSalidaReal']);
//                $fichaSalida->setFechaSalidaReal(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaRegresoPrevista']);
                $fichaSalida->setFechaRegresoPrevista(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

//                $temp = explode('/', $request->request->all()['ficha_salida']['fechaRegresoReal']);
//                $fichaSalida->setFechaRegresoReal(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaEmisionPasaporte']);
                $fichaSalida->setFechaEmisionPasaporte(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['ficha_salida']['fechaCaducidadPasaporte']);
                $fichaSalida->setFechaCaducidadPasaporte(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                if (!empty($_FILES['ficha_salida']['name']['cartaInvitacion'])) {
                    if ($fichaSalida->getCartaInvitacion() != null) {
                        if (file_exists('uploads/tramites/ficha_salida/carta_invitacion/' . $fichaSalida->getCartaInvitacion())) {
                            unlink('uploads/tramites/ficha_salida/carta_invitacion/' . $fichaSalida->getCartaInvitacion());
                        }
                    }
                    $file = $form['cartaInvitacion']->getData();
                    $file_name = $_FILES['ficha_salida']['name']['cartaInvitacion'];
                    $fichaSalida->setCartaInvitacion($file_name);
                    $file->move("uploads/tramites/ficha_salida/carta_invitacion", $file_name);
                }

                foreach ($conceptoGastosAsociados as $value) {
                    $fichaSalidaConceptoGastoRepository->remove($value, true);
                }

                foreach ($allPost['ficha_salida']['conceptoGasto'] as $value) {
                    $conceptoGasto = new FichaSalidaConceptoGasto();
                    $conceptoGasto->setFichaSalida($fichaSalida);
                    $conceptoGasto->setConceptoGasto($conceptoGastoRepository->find($value));
                    $fichaSalidaConceptoGastoRepository->add($conceptoGasto, true);
                }

                $fichaSalidaRepository->edit($fichaSalida);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }
            $conceptos = [];
            foreach ($conceptoGastosAsociados as $value) {
                $conceptos[] = $value->getConceptoGasto()->getId();
            }
            return $this->render('modules/tramite/ficha_salida/edit.html.twig', [
                'form' => $form->createView(),
                'fichaSalida' => $fichaSalida,
                'persona' => $fichaSalida->getPersona(),
                'conceptoGastosAsociados' => json_encode($conceptos)
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


    /**
     * @Route("/{id}/cambiar_estado", name="app_ficha_salida_cambiar_estado", methods={"GET", "POST"})
     * @param Request $request
     * @param FichaSalida $fichaSalida
     * @param FichaSalidaEstadoRepository $fichaSalidaEstadoRepository
     * @return Response
     */
    public function cambiarEstado(Request $request, FichaSalida $fichaSalida, EstadoFichaSalidaRepository $estadoFichaSalidaRepository, DocumentoSalidaRepository $documentoSalidaRepository, FichaSalidaEstadoRepository $fichaSalidaEstadoRepository, FichaSalidaRepository $fichaSalidaRepository)
    {
        try {
            $entidad = new FichaSalidaEstado();
            $form = $this->createForm(CambioEstadoSalidaType::class, $entidad, ['action' => 'modificar', 'estadoActual' => $fichaSalida->getEstadoFichaSalida()->getId()]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entidad->setFichaSalida($fichaSalida);
                $fichaSalidaEstadoRepository->edit($entidad);

                $fichaSalida->setEstadoFichaSalida($entidad->getEstadoFichaSalida());
                $fichaSalidaRepository->edit($fichaSalida, true);

                if ($entidad->getEstadoFichaSalida()->getId() == $this->getParameter('estado_salida_aprobada_rd')) {//Aprobada por RD
                    $documentoSalida = new DocumentoSalida();
                    $documentoSalida->setFichaSalida($fichaSalida);
                    $documentoSalida->setConceptoSalida($fichaSalida->getConceptoSalida());
                    $documentoSalida->setEstadoFichaSalida($estadoFichaSalidaRepository->find($this->getParameter('estado_salida_revision')));
                    $documentoSalida->setFechaSalidaReal($fichaSalida->getFechaSalidaReal());
                    $documentoSalida->setPais($fichaSalida->getPais());
                    $documentoSalida->setCartaInvitacion($fichaSalida->getCartaInvitacion());
                    $documentoSalida->setNumeroPasaporte($fichaSalida->getNumeroPasaporte());
                    $documentoSalida->setTipoPasaporte($fichaSalida->getTipoPasaporte());
                    $documentoSalida->setFechaCaducidadPasaporte($fichaSalida->getFechaCaducidadPasaporte());
                    $documentoSalida->setFechaEmisionPasaporte($fichaSalida->getFechaEmisionPasaporte());
                    $documentoSalida->setFechaSalidaPrevista($fichaSalida->getFechaSalidaPrevista());
                    $documentoSalida->setFechaRegresoPrevista($fichaSalida->getFechaRegresoPrevista());
                    $documentoSalida->setObjetivo($fichaSalida->getObjetivo());
                    $documentoSalida->setInstitucionCubana($fichaSalida->getInstitucionCubana());
                    $documentoSalida->setInstitucionExtranjera($fichaSalida->getInstitucionExtranjera());
                    $documentoSalida->setTiempoEstancia($fichaSalida->getTiempoEstancia());
                    $documentoSalida->setPersona($fichaSalida->getPersona());
                    $documentoSalidaRepository->add($documentoSalida, true);
                }
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_ficha_salida_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/tramite/ficha_salida/cambioEstado.html.twig', [
                'form' => $form->createView(),
                'fichaSalida' => $fichaSalida,
                'persona' => $fichaSalida->getPersona()
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_ficha_salida_cambiar_estado', ['id' => $fichaSalida->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
