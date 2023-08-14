<?php

namespace App\Controller\Institucion;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionCentrosEstudios;
use App\Entity\Institucion\InstitucionEditorial;
use App\Entity\Institucion\InstitucionFacultades;
use App\Entity\Institucion\InstitucionRecursoHumano;
use App\Entity\Institucion\InstitucionRedes;
use App\Entity\Institucion\InstitucionRedesSociales;
use App\Entity\Institucion\InstitucionRevistaCientifica;
use App\Entity\Institucion\InstitucionSedes;
use App\Entity\Security\User;
use App\Export\Institucion\ExportListInstitucionToPdf;
use App\Export\Institucion\ExportListPlanEstudioToPdf;
use App\Form\Institucion\InstitucionCentrosEstudiosType;
use App\Form\Institucion\InstitucionEditorialesType;
use App\Form\Institucion\InstitucionFacultadesType;
use App\Form\Institucion\InstitucionRedesSocialesType;
use App\Form\Institucion\InstitucionRedesType;
use App\Form\Institucion\InstitucionRevistaCientificaType;
use App\Form\Institucion\InstitucionSedesType;
use App\Form\Institucion\InstitucionType;
use App\Form\Institucion\NombreDescripcionType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Institucion\InstitucionCentrosEstudiosRepository;
use App\Repository\Institucion\InstitucionEditorialRepository;
use App\Repository\Institucion\InstitucionFacultadesRepository;
use App\Repository\Institucion\InstitucionRecursoHumanoRepository;
use App\Repository\Institucion\InstitucionRedesRepository;
use App\Repository\Institucion\InstitucionRedesSocialesRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Institucion\InstitucionRevistaCientificaRepository;
use App\Repository\Institucion\InstitucionSedesRepository;
use App\Repository\Institucion\RecursosHumanosRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Services\TraceService;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/institucion/institucion")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class InstitucionController extends AbstractController
{

    /**
     * @Route("/", name="app_institucion_index", methods={"GET"})
     * @param InstitucionRepository $institucionRepository
     * @return Response
     */
    public function index(InstitucionRepository $institucionRepository)
    {
        return $this->render('modules/institucion/institucion/index.html.twig', [
            'registros' => $institucionRepository->getInstituciones(),
        ]);
    }

    /**
     * @Route("/registrar", name="app_institucion_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param InstitucionRepository $institucionRepository
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $requestStack
     * @return Response
     */
    public function registrar(Request $request, InstitucionRepository $institucionRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        try {
            $institucion = new Institucion();
            $form = $this->createForm(InstitucionType::class, $institucion, ['action' => 'registrar', 'idCategoriaEstructura' => $this->getParameter('id_categoria_estructura_ies')]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $institucion->setFechaFundacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['institucion']['fechaFundacion']));

                $institucion->setNombre($institucion->getEstructura()->getNombre());
                $institucion->setTelefono($institucion->getEstructura()->getTelefono());
                $institucion->setCorreo($institucion->getEstructura()->getEmail());
                $institucion->setSiglas($institucion->getEstructura()->getSiglas());
                $institucion->setDireccionSedePrincipal($institucion->getEstructura()->getDireccion());
                $institucion->setCoordenadasSedePrincipal($institucion->getEstructura()->getUbicacion());

                if (!empty($_FILES['institucion']['name']['logo'])) {
                    $file = $form['logo']->getData();
                    $file_name = $_FILES['institucion']['name']['logo'];
                    $institucion->setLogo($file_name);
                    $file->move("uploads/institucion/logo", $file_name);
                }
                if (!empty($_FILES['institucion']['name']['organigrama'])) {
                    $file = $form['organigrama']->getData();
                    $file_name = $_FILES['institucion']['name']['organigrama'];
                    $institucion->setOrganigrama($file_name);
                    $file->move("uploads/institucion/organigrama", $file_name);
                }
                $institucionRepository->add($institucion, true);

                $traceService = new TraceService($requestStack, $entityManager, $serializer);
                $traceService->registrar($this->getParameter('accion_registrar'), $this->getParameter('objeto_institucion'), null, $institucion, $this->getParameter('tipo_traza_negocio'));

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_institucion_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $institucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function modificar(Request $request, Institucion $institucion, InstitucionRepository $tipoInstitucionRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        try {
            $dataAnterior = $institucion;
            $form = $this->createForm(InstitucionType::class, $institucion, ['action' => 'modificar', 'idCategoriaEstructura' => $this->getParameter('id_categoria_estructura_ies')]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $institucion->setNombre($institucion->getEstructura()->getNombre());
                $institucion->setTelefono($institucion->getEstructura()->getTelefono());
                $institucion->setCorreo($institucion->getEstructura()->getEmail());
                $institucion->setSiglas($institucion->getEstructura()->getSiglas());
                $institucion->setDireccionSedePrincipal($institucion->getEstructura()->getDireccion());
                $institucion->setCoordenadasSedePrincipal($institucion->getEstructura()->getUbicacion());

                $temp = explode('/', $request->request->all()['institucion']['fechaFundacion']);
                $institucion->setFechaFundacion(new \DateTime($temp[1] . '/' . $temp[0] . '/' . $temp[2]));

                if (!empty($form['logo']->getData())) {
                    if ($institucion->getLogo() != null) {
                        if (file_exists('uploads/institucion/logo/' . $institucion->getLogo())) {
                            unlink('uploads/institucion/logo/' . $institucion->getLogo());
                        }
                    }
                    $file = $form['logo']->getData();
                    $file_name = $_FILES['institucion']['name']['logo'];
                    $institucion->setLogo($file_name);
                    $file->move("uploads/institucion/logo", $file_name);
                }


                if (!empty($_FILES['institucion']['name']['organigrama'])) {
                    if ($institucion->getOrganigrama() != null) {
                        if (file_exists('uploads/institucion/organigrama/' . $institucion->getOrganigrama())) {
                            unlink('uploads/institucion/organigrama/' . $institucion->getOrganigrama());
                        }
                    }
                    $file = $form['organigrama']->getData();
                    $file_name = $_FILES['institucion']['name']['organigrama'];

                    $institucion->setOrganigrama($file_name);
                    $file->move("uploads/institucion/organigrama", $file_name);
                }


                $tipoInstitucionRepository->edit($institucion);

                $traceService = new TraceService($requestStack, $entityManager, $serializer);
                $traceService->registrar($this->getParameter('accion_modificar'), $this->getParameter('objeto_institucion'), $dataAnterior, $institucion, $this->getParameter('tipo_traza_negocio'));

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/edit.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_institucion_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param InstitucionRedesRepository $institucionRedesRepository
     * @param InstitucionRedesSocialesRepository $institucionRedesSocialesRepository
     * @param InstitucionSedesRepository $institucionSedesRepository
     * @param InstitucionRevistaCientificaRepository $institucionRevistaCientificaRepository
     * @param InstitucionCentrosEstudiosRepository $institucionCentrosEstudiosRepository
     * @param Institucion $institucion
     * @param InstitucionEditorialRepository $institucionEditorialRepository
     * @param InstitucionFacultadesRepository $institucionFacultadesRepository
     * @return Response
     */
    public function detail(Request $request, InstitucionRecursoHumanoRepository $institucionRecursoHumanoRepository, InstitucionRedesRepository $institucionRedesRepository, InstitucionRedesSocialesRepository $institucionRedesSocialesRepository, InstitucionSedesRepository $institucionSedesRepository, InstitucionRevistaCientificaRepository $institucionRevistaCientificaRepository, InstitucionCentrosEstudiosRepository $institucionCentrosEstudiosRepository, Institucion $institucion, InstitucionEditorialRepository $institucionEditorialRepository, InstitucionFacultadesRepository $institucionFacultadesRepository)
    {
        return $this->render('modules/institucion/institucion/detail.html.twig', [
            'item' => $institucion,
            'editoriales' => $institucionEditorialRepository->findBy(['institucion' => $institucion->getId()]),
            'facultades' => $institucionFacultadesRepository->findBy(['institucion' => $institucion->getId()]),
            'centrosEstudios' => $institucionCentrosEstudiosRepository->findBy(['institucion' => $institucion->getId()]),
            'revistas' => $institucionRevistaCientificaRepository->findBy(['institucion' => $institucion->getId()]),
            'sedes' => $institucionSedesRepository->findBy(['institucion' => $institucion->getId()]),
            'redesSociales' => $institucionRedesSocialesRepository->findBy(['institucion' => $institucion->getId()]),
            'redes' => $institucionRedesRepository->findBy(['institucion' => $institucion->getId()]),
            'recursosHumanos' => $institucionRecursoHumanoRepository->findBy(['institucion' => $institucion->getId()]),
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_institucion_eliminar", methods={"GET"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function eliminar(Request $request, Institucion $institucion, InstitucionRepository $tipoInstitucionRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        try {
            if ($tipoInstitucionRepository->find($institucion) instanceof Institucion) {
                $tipoInstitucionRepository->remove($institucion, true);

                $traceService = new TraceService($requestStack, $entityManager, $serializer);
                $traceService->registrar($this->getParameter('accion_eliminar'), $this->getParameter('objeto_institucion'), null, $institucion, $this->getParameter('tipo_traza_negocio'));


                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_facultades", name="app_institucion_asignar_facultades", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionFacultadesRepository $institucionFacultadesRepository
     * @return Response
     */
    public function asignarFacultad(Request $request, Institucion $institucion, InstitucionFacultadesRepository $institucionFacultadesRepository, EstructuraRepository $estructuraRepository)
    {
        try {
            $entidad = new InstitucionFacultades();
            $form = $this->createForm(InstitucionFacultadesType::class, $entidad, ['idEstructura' => $institucion->getEstructura()->getId(), 'idCategoriaEstructura' => $this->getParameter('id_categoria_estructura_facultad')]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $institucionFacultadesRepository->findBy(['institucion' => $institucion->getId(), 'estructura' => $request->request->all()['institucion_facultades']['estructura']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $entidad->setNombreFacultad($estructuraRepository->find($request->request->all()['institucion_facultades']['estructura'])->getNombre());
                    $institucionFacultadesRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_facultades', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_facultades', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_facultades.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $institucionFacultadesRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_facultades', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_facultad", name="app_institucion_eliminar_facultad", methods={"GET"})
     * @param Request $request
     * @param InstitucionFacultades $institucionFacultades
     * @param InstitucionRepository $institucionFacultadesRepositoryy
     * @return Response
     */
    public function eliminarFacultad(Request $request, InstitucionFacultades $institucionFacultades, InstitucionFacultadesRepository $institucionFacultadesRepositoryy)
    {
        try {
            if ($institucionFacultades instanceof InstitucionFacultades) {
                $institucionFacultadesRepositoryy->remove($institucionFacultades, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_facultades', ['id' => $institucionFacultades->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_facultades', ['id' => $institucionFacultades->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_facultades', ['id' => $institucionFacultades->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_centros_estudio", name="app_institucion_asignar_centros_estudio", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionCentrosEstudiosRepository $institucionCentrosEstudiosRepository
     * @return Response
     */
    public function asignarCentrosEstudios(Request $request, Institucion $institucion, InstitucionCentrosEstudiosRepository $institucionCentrosEstudiosRepository)
    {
        try {
            $entidad = new InstitucionCentrosEstudios();
            $form = $this->createForm(InstitucionCentrosEstudiosType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $institucionCentrosEstudiosRepository->findBy(['institucion' => $institucion->getId(), 'nombreCentroEstudio' => $request->request->all()['institucion_centros_estudios']['nombreCentroEstudio']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $institucionCentrosEstudiosRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_centros_estudio', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_centros_estudio', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_centros_estudios.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $institucionCentrosEstudiosRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_centros_estudio', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_centro_estudio", name="app_institucion_eliminar_centro_estudio", methods={"GET"})
     * @param Request $request
     * @param InstitucionFacultades $institucionCentroEstudio
     * @param InstitucionRepository $institucionCentrosEstudiosRepository
     * @return Response
     */
    public function eliminarCentroEstudio(Request $request, InstitucionCentrosEstudios $institucionCentroEstudio, InstitucionCentrosEstudiosRepository $institucionCentrosEstudiosRepository)
    {
        try {
            if ($institucionCentroEstudio instanceof InstitucionCentrosEstudios) {
                $institucionCentrosEstudiosRepository->remove($institucionCentroEstudio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_centros_estudio', ['id' => $institucionCentroEstudio->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_centros_estudio', ['id' => $institucionCentroEstudio->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_centros_estudio', ['id' => $institucionCentroEstudio->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_sede", name="app_institucion_asignar_sede", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionSedesRepository $institucionSedeRepository
     * @return Response
     */
    public function asignarSedes(Request $request, Institucion $institucion, InstitucionSedesRepository $institucionSedeRepository)
    {
        try {
            $entidad = new InstitucionSedes();
            $form = $this->createForm(InstitucionSedesType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $institucionSedeRepository->findBy(['institucion' => $institucion->getId(), 'nombreSede' => $request->request->all()['institucion_sedes']['nombreSede']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $institucionSedeRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_sedes.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $institucionSedeRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_sede", name="app_institucion_eliminar_sede", methods={"GET"})
     * @param Request $request
     * @param InstitucionSedes $institucionSedes
     * @param InstitucionSedesRepository $institucionSedesRepository
     * @return Response
     */
    public function eliminarSede(Request $request, InstitucionSedes $institucionSedes, InstitucionSedesRepository $institucionSedesRepository)
    {
        try {
            if ($institucionSedes instanceof InstitucionSedes) {
                $institucionSedesRepository->remove($institucionSedes, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucionSedes->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucionSedes->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucionSedes->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_redes_sociales", name="app_institucion_asignar_redes_sociales", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionFacultadesRepository $redesSocialesRepository
     * @return Response
     */
    public function asignarRedSocial(Request $request, Institucion $institucion, InstitucionRedesSocialesRepository $redesSocialesRepository)
    {
        try {
            $entidad = new InstitucionRedesSociales();
            $form = $this->createForm(InstitucionRedesSocialesType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $exist = $redesSocialesRepository->findBy(['institucion' => $institucion->getId(), 'redSocial' => $request->request->all()['institucion_redes_sociales']['redSocial']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $redesSocialesRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_redes_sociales', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_redes_sociales', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_redes_sociales.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $redesSocialesRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_redes_sociales', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_red_social", name="app_institucion_eliminar_red_social", methods={"GET"})
     * @param Request $request
     * @param InstitucionFacultades $institucionRedesSociales
     * @param InstitucionRepository $institucionRedesSocialesRepository
     * @return Response
     */
    public function eliminarRedSocial(Request $request, InstitucionRedesSociales $institucionRedesSociales, InstitucionRedesSocialesRepository $institucionRedesSocialesRepository)
    {
        try {
            if ($institucionRedesSociales instanceof InstitucionRedesSociales) {
                $institucionRedesSocialesRepository->remove($institucionRedesSociales, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_redes_sociales', ['id' => $institucionRedesSociales->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_redes_sociales', ['id' => $institucionRedesSociales->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_redes_sociales', ['id' => $institucionRedesSociales->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_editoriales", name="app_institucion_asignar_editoriales", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionFacultadesRepository $institucionEditorialRepository
     * @return Response
     */
    public function asignarEditorial(Request $request, Institucion $institucion, InstitucionEditorialRepository $institucionEditorialRepository)
    {
        try {
            $entidad = new InstitucionEditorial();
            $form = $this->createForm(InstitucionEditorialesType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $institucionEditorialRepository->findBy(['institucion' => $institucion->getId(), 'nombreEditorial' => $request->request->all()['institucion_editoriales']['nombreEditorial']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $institucionEditorialRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_editoriales', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_editoriales', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_aditoriales.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $institucionEditorialRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_editoriales', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_editorial", name="app_institucion_eliminar_editorial", methods={"GET"})
     * @param Request $request
     * @param InstitucionEditorial $institucionEditorial
     * @param InstitucionEditorialRepository $institucionEditorialRepository
     * @return Response
     */
    public function eliminarEditorial(Request $request, InstitucionEditorial $institucionEditorial, InstitucionEditorialRepository $institucionEditorialRepository)
    {
        try {
            if ($institucionEditorial instanceof InstitucionEditorial) {
                $institucionEditorialRepository->remove($institucionEditorial, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_editoriales', ['id' => $institucionEditorial->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_editoriales', ['id' => $institucionEditorial->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_editoriales', ['id' => $institucionEditorial->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_redes", name="app_institucion_asignar_redes", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionFacultadesRepository $redesRepository
     * @return Response
     */
    public function asignarRedes(Request $request, Institucion $institucion, InstitucionRedesRepository $redesRepository)
    {
        try {
            $entidad = new InstitucionRedes();
            $form = $this->createForm(InstitucionRedesType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $exist = $redesRepository->findBy(['institucion' => $institucion->getId(), 'nombreRed' => $request->request->all()['institucion_redes']['nombreRed']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $redesRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_redes', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_redes', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_redes.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $redesRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_redes', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_red", name="app_institucion_eliminar_red", methods={"GET"})
     * @param Request $request
     * @param InstitucionRedes $institucionRedes
     * @param InstitucionRedesRepository $institucionRedesRepository
     * @return Response
     */
    public function eliminarRed(Request $request, InstitucionRedes $institucionRedes, InstitucionRedesRepository $institucionRedesRepository)
    {
        try {
            if ($institucionRedes instanceof InstitucionRedes) {
                $institucionRedesRepository->remove($institucionRedes, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_redes', ['id' => $institucionRedes->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_redes', ['id' => $institucionRedes->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_redes', ['id' => $institucionRedes->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/asignar_revistas", name="app_institucion_asignar_revistas", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param InstitucionRevistaCientificaRepository $institucionRevistaCientificaRepository
     * @return Response
     */
    public function asignarRevistas(Request $request, Institucion $institucion, InstitucionRevistaCientificaRepository $institucionRevistaCientificaRepository)
    {
        try {
            $entidad = new InstitucionRevistaCientifica();
            $form = $this->createForm(InstitucionRevistaCientificaType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $institucionRevistaCientificaRepository->findBy(['institucion' => $institucion->getId(), 'nombreRevista' => $request->request->all()['institucion_revista_cientifica']['nombreRevista']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
                    $institucionRevistaCientificaRepository->add($entidad, true);

                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_institucion_asignar_revistas', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'El elemento ya existe.');
                return $this->redirectToRoute('app_institucion_asignar_revistas', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/institucion/institucion/asignar_revistas.html.twig', [
                'form' => $form->createView(),
                'institucion' => $institucion,
                'registros' => $institucionRevistaCientificaRepository->findBy(['institucion' => $institucion->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_revistas', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_revista", name="app_institucion_eliminar_revista", methods={"GET"})
     * @param Request $request
     * @param InstitucionRevistaCientifica $institucionRevistaCientifica
     * @param InstitucionRevistaCientificaRepository $institucionRevistaCientificaRepository
     * @return Response
     */
    public function eliminarRevista(Request $request, InstitucionRevistaCientifica $institucionRevistaCientifica, InstitucionRevistaCientificaRepository $institucionRevistaCientificaRepository)
    {
        try {
            if ($institucionRevistaCientifica instanceof InstitucionRevistaCientifica) {
                $institucionRevistaCientificaRepository->remove($institucionRevistaCientifica, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_institucion_asignar_revistas', ['id' => $institucionRevistaCientifica->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_institucion_asignar_revistas', ['id' => $institucionRevistaCientifica->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_asignar_revistas', ['id' => $institucionRevistaCientifica->getInstitucion()->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/programas_formacion/{option}", name="app_institucion_programas_formacion", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @return Response
     */
    public function programasFormacion(Request $request, Institucion $institucion, $option, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $postgrados = [];
            $carreras = [];
            if ($option == 'option1') {
                $carreras = [];
            }
            if ($option == 'option2') {
                $postgrados = $solicitudProgramaRepository->findBy(['universidad' => $institucion->getId()]);
            }
            return $this->render('modules/institucion/institucion/listar_programas_formacion.html.twig', [
                'carreras' => $carreras,
                'postgrados' => $postgrados,
                'institucion' => $institucion,
                'option' => $option
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/asignar_recursos_humanos", name="app_institucion_asignar_recursos_humanos", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param RecursosHumanosRepository $recursosHumanosRepository
     * @param InstitucionRecursoHumanoRepository $institucionRecursoHumanoRepository
     * @return Response
     */
    public function asignarRecursosHumanos(Request $request, Institucion $institucion, RecursosHumanosRepository $recursosHumanosRepository, InstitucionRecursoHumanoRepository $institucionRecursoHumanoRepository)
    {
        try {
            $allPost = $request->request->all();
            if (isset($allPost['id']) && !empty($allPost['id']) && isset($allPost['valor']) && !empty($allPost['valor'])) {
                $entidad = $institucionRecursoHumanoRepository->findOneBy(['institucion' => $institucion->getId(), 'recursoHumano' => $allPost['id']]);
                if (empty($entidad)) {
                    $entidad = new InstitucionRecursoHumano();
                }
                $entidad->setInstitucion($institucion);
                $entidad->setCantidad($allPost['valor']);
                $entidad->setRecursoHumano($recursosHumanosRepository->find($allPost['id']));
                $institucionRecursoHumanoRepository->edit($entidad, true);
            }


            $recursosHumanosAsignados = [];
            $rrhhAsignados = $institucionRecursoHumanoRepository->findBy(['institucion' => $institucion->getId()]);
            if (is_array($rrhhAsignados)) {
                foreach ($rrhhAsignados as $value) {
                    $recursosHumanosAsignados[$value->getRecursoHumano()->getId()] = $value->getCantidad();
                }
            }

            return $this->render('modules/institucion/institucion/asignar_recursos_humanos.html.twig', [
                'institucion' => $institucion,
                'recursosHumanos' => $recursosHumanosRepository->findBy(['activo' => true]),
                'recursosHumanosAsignados' => $recursosHumanosAsignados
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/{tab}/internacionalizacion", name="app_institucion_internacionalizacion", methods={"GET", "POST"})
     * @param Request $request
     * @param Institucion $institucion
     * @param Utils $utils
     * @return Response
     */
    public function internacionalizacion(Request $request, $tab, Institucion $institucion, Utils $utils)
    {
        try {
            $params['tabSelected'] = $tab;
            $params['institucion'] = $institucion;
            $params['id'] = $institucion->getId();
            if ($tab == 'programa') {
                $params['programasColaboracion'] = $utils->obtenerProgramasColaboracion($institucion->getCodigo());
                $params['mecanismosColaboracion'] = [];
                $params['proyectos'] = [];
                $params['membresias'] = [];
            }
            if ($tab == 'proyecto') {
                $params['proyectos'] = $utils->obtenerProyectos($institucion->getCodigo());
                $params['mecanismosColaboracion'] = [];
                $params['programasColaboracion'] = [];
                $params['membresias'] = [];
            }
            if ($tab == 'membresia') {
                $params['membresias'] = $utils->obtenerMembresias($institucion->getCodigo());
                $params['mecanismosColaboracion'] = [];
                $params['programasColaboracion'] = [];
                $params['proyectos'] = [];
            }
            if ($tab == 'mecanismo') {
                $params['mecanismosColaboracion'] = $utils->obtenerMecanismosColaboracion($institucion->getCodigo());
                $params['membresias'] = [];
                $params['programasColaboracion'] = [];
                $params['proyectos'] = [];
            }

//        pr($params);
            return $this->render('modules/institucion/institucion/internacionalizacion.html.twig', $params);

        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_institucion_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/exportar_pdf", name="app_institucion_exportar_pdf", methods={"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function exportarPdf(Request $request, \App\Services\HandlerFop $handFop, InstitucionRepository $institucionRepository)
    {
        $export = $institucionRepository->getInstituciones();
        $export = \App\Services\DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new ExportListInstitucionToPdf($export));
    }
}
