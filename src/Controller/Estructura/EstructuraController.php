<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Plaza;
use App\Entity\Security\User;
use App\Form\Estructura\EstructuraType;
use App\Form\Estructura\PlazaType;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Estructura\MunicipioRepository;
use App\Repository\Estructura\PlazaRepository;
use App\Repository\Estructura\ProvinciaRepository;
use App\Repository\Estructura\TipoEstructuraRepository;
use App\Services\Utils;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/estructura/estructura")
 */
class EstructuraController extends AbstractController
{

    /**
     * @Route("/", name="app_estructura_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function index(Request $request, EstructuraRepository $estructuraRepository)
    {
        try {
            $registros = $estructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
            return $this->render('modules/estructura/estructura/index.html.twig', [
                'registros' => $registros,
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/arbol", name="app_estructura_index_arbol", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function indexArbol(EstructuraRepository $estructuraRepository)
    {
        $element = $estructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        $registros = [];
        foreach ($element as $el) {
            $aux = [];
            $aux['id'] = $el->getId();
            $aux['parent'] = $el->getEstructura() ? $el->getEstructura()->getId() : '#';
            $aux['text'] = $el->getNombre();
            $aux['color'] = method_exists($el->getCategoriaEstructura(), 'getColor') ? $el->getCategoriaEstructura()->getColor() : null;
            $registros[] = $aux;
        }

        return $this->render('modules/estructura/estructura/arbol.html.twig', [
            'registros' => json_encode($registros),
        ]);

    }

    /**
     * @Route("/estructuras/mapa", name="app_estructura_index_mapa", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function indexMapa(EstructuraRepository $estructuraRepository)
    {
        $element = $estructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        return $this->render('modules/estructura/estructura/mapa.html.twig', [
            'registros' => $element
        ]);
    }

    /**
     * @Route("/registrar", name="app_estructura_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function registrar(Request $request, EstructuraRepository $estructuraRepository, MunicipioRepository $municipioRepository)
    {
        try {
            $estructuraEntity = new Estructura();
            $form = $this->createForm(EstructuraType::class, $estructuraEntity, ['action' => 'registrar', 'data_choices' => -1]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $estructuraEntity->setFechaActivacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['estructura']['fechaActivacion']));
                $estructuraEntity->setMunicipio($municipioRepository->find($request->request->all()['estructura']['municipio']));
                $estructuraRepository->add($estructuraEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('modules/estructura/estructura/new.html.twig', [
                'form' => $form->createView(),
                'accion' => 'add'
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_estructura_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $estructura
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function modificar(Request $request, Estructura $estructura, EstructuraRepository $estructuraRepository, MunicipioRepository $municipioRepository)
    {
        try {
            $form = $this->createForm(EstructuraType::class, $estructura, ['action' => 'modificar', 'data_choices' => $estructura->getProvincia()->getId()]);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $estructura->setMunicipio($municipioRepository->find($request->request->all()['estructura']['municipio']));
                $estructura->setFechaActivacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['estructura']['fechaActivacion']));
                $estructuraRepository->edit($estructura);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/estructura/edit.html.twig', [
                'form' => $form->createView(),
                'estructura' => $estructura
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_modificar', ['id' => $estructura], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_estructura_eliminar", methods={"GET"})
     * @param Request $request
     * @param Estructura $estructura
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function eliminar(Request $request, Estructura $estructura, EstructuraRepository $estructuraRepository)
    {
        try {
            if ($estructuraRepository->find($estructura) instanceof Estructura) {
                $estructuraRepository->remove($estructura, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_estructura_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Estructura $estructura
     * @param Plaza $plazaRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function detail(Request $request, Estructura $estructura, PlazaRepository $plazaRepository)
    {
        return $this->render('modules/estructura/estructura/detail.html.twig', [
            'item' => $estructura,
            'registros' => $plazaRepository->findBy(['estructura' => $estructura], ['responsabilidad' => 'asc']),
        ]);
    }

    /**
     * @Route("/{id}/plaza", name="app_estructura_plaza", methods={"GET", "POST"})
     * @param Request $request
     * @param Estructura $estructura
     * @param PlazaRepository $plazaRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function plaza(Request $request, Estructura $estructura, PlazaRepository $plazaRepository)
    {
        try {
            $plaza = new Plaza();
            $form = $this->createForm(PlazaType::class, $plaza);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $plaza->setEstructura($estructura);
                $plazaRepository->add($plaza, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estructura_plaza', ['id' => $estructura->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->render('modules/estructura/estructura/plaza.html.twig', [
                'form' => $form->createView(),
                'registros' => $plazaRepository->findBy(['estructura' => $estructura], ['responsabilidad' => 'asc']),
                'estructura' => $estructura
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            $this->addFlash('error', "El elemento ya existe ");
            return $this->redirectToRoute('app_estructura_plaza', ['id' => $estructura->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Han ocurrido errores al procesar el formulario.');
            return $this->redirectToRoute('app_estructura_plaza', ['id' => $estructura->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar-plaza", name="app_estructura_eliminar_plaza", methods={"GET"})
     * @param Request $request
     * @param Plaza $plaza
     * @param PlazaRepository $plazaRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function eliminarPlaza(Request $request, Plaza $plaza, PlazaRepository $plazaRepository)
    {
        try {
            if ($plazaRepository->find($plaza) instanceof Plaza) {
                $plazaRepository->remove($plaza, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_estructura_plaza', ['id' => $plaza->getEstructura()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_estructura_plaza', ['id' => $plaza->getEstructura()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_plaza', ['id' => $plaza->getEstructura()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/registrar-estructura-hija", name="app_estructura_registrar_hija", methods={"GET", "POST"})
     * @param Request $request
     * @param Estructura $estructura
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function registrarEstructuraHija(Request $request, ProvinciaRepository $provinciaRepository, MunicipioRepository $municipioRepository, TipoEstructuraRepository $tipoEstructuraRepository, CategoriaEstructuraRepository $categoriaEstructuraRepository, Estructura $estructura, EstructuraRepository $estructuraRepository, EntityManagerInterface $entityManager)
    {
        try {
            $nuevaEstruc = new Estructura();
            $nuevaEstruc->setEstructura($estructura);

            $form = $this->createForm(EstructuraType::class, $nuevaEstruc, ['action' => 'modificar', 'data_choices' => -1]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $nueva = new Estructura();

                $nueva->setNombre($request->request->all()['estructura']['nombre']);
                $nueva->setDireccion($request->request->all()['estructura']['direccion']);
                $nueva->setUbicacion($request->request->all()['estructura']['ubicacion']);
                $nueva->setSiglas($request->request->all()['estructura']['siglas']);
                $nueva->setTelefono($request->request->all()['estructura']['telefono']);
                $nueva->setActivo($request->request->all()['estructura']['activo']);
                $nueva->setEmail($request->request->all()['estructura']['email']);
                $nueva->setFechaActivacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['estructura']['fechaActivacion']));
                $nueva->setEstructura($estructuraRepository->find($request->request->all()['estructura']['estructura']));
                $nueva->setTipoEstructura($tipoEstructuraRepository->find($request->request->all()['estructura']['tipoEstructura']));
                $nueva->setCategoriaEstructura($categoriaEstructuraRepository->find($request->request->all()['estructura']['categoriaEstructura']));
                $nueva->setProvincia($provinciaRepository->find($request->request->all()['estructura']['provincia']));
                $nueva->setMunicipio($municipioRepository->find($request->request->all()['estructura']['municipio']));

                $entityManager->persist($nueva);
                $entityManager->flush();
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_estructura_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/estructura/estructura/new.html.twig', [
                'form' => $form->createView(),
                'accion' => 'add-hija'
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * Add package entity.
     *
     * @Route("/{id}/estructura_dado_categoria", name="app_estructura_dado_categoria", methods={"GET"})
     * @param Request $request
     * @param $id
     * @param EstructuraRepository $estructuraRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getEstructuraDadoCategoria(Request $request, $id, EstructuraRepository $estructuraRepository, Utils $utils): JsonResponse
    {
        try {
//            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
//            return $this->json($utils->procesarNomenclador($estructuraRepository->geEstructurasDadoArrayEstructuras($id, $estructurasNegocio)));
            return $this->json($utils->procesarNomenclador($estructuraRepository->geEstructurasDadoArrayEstructurasTemp($id)));

        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }

    /**
     * @Route("/exportar_pdf", name="app_estructura_exportar_pdf", methods={"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function exportarPdf(Request $request, \App\Services\HandlerFop $handFop, EstructuraRepository $estructuraRepository)
    {
        $export = $estructuraRepository->getExportarListado();
        $export = \App\Services\DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new \App\Export\Estructura\ExportListEstructuraToPdf($export));
    }

    /**
     * Add package entity.
     *
     * @Route("/{id}/estructura_dado_id", name="app_estructura_dado_id", methods={"GET"})
     * @param Request $request
     * @param $id
     * @param MunicipioRepository $estructuraRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getEstructuraDadoId(Request $request, $id, EstructuraRepository $estructuraRepository, Utils $utils): JsonResponse
    {
        try {
            return $this->json($utils->procesarEstructura($estructuraRepository->find($id)));
        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }
}
