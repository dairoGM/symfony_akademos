<?php

namespace App\Controller\Estructura;

use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Plaza;
use App\Entity\Security\User;
use App\Export\Estructura\ExportListEstructuraToPdf;
use App\Form\Estructura\EstructuraType;
use App\Form\Estructura\PlazaType;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Estructura\MunicipioRepository;
use App\Repository\Estructura\PlazaRepository;
use App\Repository\Estructura\ProvinciaRepository;
use App\Repository\Estructura\TipoEstructuraRepository;
use App\Services\HandlerFop;
use App\Services\Utils;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
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

    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __construct(ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port)
    {
        $this->env = $container->get('env_config');
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;


        $connectionParams = array(
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'port' => $this->port,
            'driver' => $this->driver,
            'charset' => 'UTF8'
        );
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    /**
     * @Route("/", name="app_estructura_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function index(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        try {
            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
            if (count($estructurasNegocio) > 0) {
                $registros = $estructuraRepository->geEstructuras($estructurasNegocio);
            } else {
                $registros = $estructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
            }
            $data = [];
            foreach ($registros as $value) {
                $data[$value->getId()] = !in_array($value->getId(), $estructurasNegocio);
            }
            return $this->render('modules/estructura/estructura/index.html.twig', [
                'registros' => $registros,
                'dataShow' => $data,
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
    public function indexArbol(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
        if (count($estructurasNegocio) > 0) {
            $element = $estructuraRepository->geEstructuras($estructurasNegocio);
        } else {
            $element = $estructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        }


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
    public function indexMapa(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
        if (count($estructurasNegocio) > 0) {
            $element = $estructuraRepository->geEstructuras($estructurasNegocio);
        } else {
            $element = $estructuraRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        }
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
    public function registrar(Request $request, EstructuraRepository $estructuraRepository, MunicipioRepository $municipioRepository, Utils $utils)
    {
        try {
            $estructuraEntity = new Estructura();
            $form = $this->createForm(EstructuraType::class, $estructuraEntity, ['action' => 'registrar', 'data_choices' => -1, 'estructuraNegocio' => $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId())]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $estructuraEntity->setFechaActivacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['estructura']['fechaActivacion']));
                $estructuraEntity->setMunicipio($municipioRepository->find($request->request->all()['estructura']['municipio']));
                $estructuraRepository->add($estructuraEntity, true);

                try {
                    $data['nombre_estructura'] = $request->request->all()['estructura']['nombre'];
                    $data['siglas'] = $request->request->all()['estructura']['siglas'];
                    $data['codigo_estructura'] = $request->request->all()['estructura']['siglas'];
                    $data['telefono'] = $request->request->all()['estructura']['telefono'];
                    $data['ubicacion'] = $request->request->all()['estructura']['ubicacion'];
                    $data['correo_electronico'] = $request->request->all()['estructura']['email'];
                    $data['id_categoria_estructura'] = $request->request->all()['estructura']['categoriaEstructura'];
                    $data['fecha_activacion'] = $request->request->all()['estructura']['fechaActivacion'];
                    $data['activo'] = 1;
                    $this->connection->insert('sq_estructura_composicion.tb_destructura', $data);
                } catch (\Exception $exception) {
                    $this->addFlash('error', $exception->getMessage());
                }

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
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT" )
     */
    public function modificar(Request $request, Estructura $estructura, EstructuraRepository $estructuraRepository, MunicipioRepository $municipioRepository, Utils $utils)
    {
        try {
            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
            $form = $this->createForm(EstructuraType::class, $estructura, ['action' => 'modificar', 'data_choices' => $estructura->getProvincia()->getId(), 'estructuraNegocio' => $estructurasNegocio]);
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
    public function registrarEstructuraHija(Request $request, ProvinciaRepository $provinciaRepository, MunicipioRepository $municipioRepository, TipoEstructuraRepository $tipoEstructuraRepository, CategoriaEstructuraRepository $categoriaEstructuraRepository, Estructura $estructura, EstructuraRepository $estructuraRepository, EntityManagerInterface $entityManager, Utils $utils)
    {
        try {
            $nuevaEstruc = new Estructura();
            $nuevaEstruc->setEstructura($estructura);

            $form = $this->createForm(EstructuraType::class, $nuevaEstruc, ['action' => 'modificar', 'data_choices' => -1, 'estructuraNegocio' => $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId())]);
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
     * @param $id
     * @param EstructuraRepository $estructuraRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getEstructuraDadoCategoria($id, EstructuraRepository $estructuraRepository, Utils $utils): JsonResponse
    {
        try {
//            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
//            return $this->json($utils->procesarNomenclador($estructuraRepository->geEstructurasDadoArrayEstructuras($id, $estructurasNegocio)));
            return $this->json($utils->procesarEstructuraV2($estructuraRepository->geEstructurasDadoArrayEstructurasTemp($id)));

        } catch (\Exception $exception) {
            return new JsonResponse([]);
        }
    }

    /**
     * Add package entity.
     *
     * @Route("/{id}/estructura_dado_entidad", name="app_estructura_dado_entidad", methods={"GET"})
     * @param $id
     * @param EstructuraRepository $estructuraRepository
     * @param Utils $utils
     * @return JsonResponse
     */
    public function getEstructuraDadoEntidad($id, EstructuraRepository $estructuraRepository, Utils $utils): JsonResponse
    {
        try {
//            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
//            return $this->json($utils->procesarNomenclador($estructuraRepository->geEstructurasDadoArrayEstructuras($id, $estructurasNegocio)));
            return $this->json($estructuraRepository->getEstructuraDadoEntidad($id));

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
    public function exportarPdf(Request $request, HandlerFop $handFop, EstructuraRepository $estructuraRepository)
    {
        $export = $estructuraRepository->getExportarListado();
        $export = \App\Services\DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new ExportListEstructuraToPdf($export));
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


    /**
     * @Route("/{id}/exportar-estructuras-hijas", name="app_estructura_exportar_estructuras_hijas", methods={"GET", "POST"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function exportarEstructurasHija($id, HandlerFop $handFop, EstructuraRepository $estructuraRepository)
    {
        try {
            $export = $estructuraRepository->getExportarListadoDadoId($id);
            $export = \App\Services\DoctrineHelper::toArray($export);
            return $handFop->exportToPdf(new ExportListEstructuraToPdf($export));
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/entidad/index", name="app_estructura_entidad_index", methods={"GET"})
     * @param EstructuraRepository $estructuraRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ESTRUCT")
     */
    public function indexEstructurasEntidades(EstructuraRepository $estructuraRepository, Utils $utils)
    {
        try {
            $registros = $estructuraRepository->findBy(['esEntidad' => 1], ['activo' => 'desc', 'id' => 'desc']);
            return $this->render('modules/estructura/estructura/entidad.html.twig', [
                'registros' => $registros
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_estructura_entidad_index', [], Response::HTTP_SEE_OTHER);
        }
    }


}
