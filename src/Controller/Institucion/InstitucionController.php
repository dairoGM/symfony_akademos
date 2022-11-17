<?php

namespace App\Controller\Institucion;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionCentrosEstudios;
use App\Entity\Institucion\InstitucionFacultades;
use App\Entity\Institucion\InstitucionSedes;
use App\Entity\Security\User;
use App\Form\Institucion\InstitucionCentrosEstudiosType;
use App\Form\Institucion\InstitucionFacultadesType;
use App\Form\Institucion\InstitucionSedesType;
use App\Form\Institucion\InstitucionType;
use App\Form\Institucion\NombreDescripcionType;
use App\Repository\Institucion\InstitucionCentrosEstudiosRepository;
use App\Repository\Institucion\InstitucionFacultadesRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Institucion\InstitucionSedesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function index(InstitucionRepository $tipoInstitucionRepository)
    {
        return $this->render('modules/institucion/institucion/index.html.twig', [
            'registros' => $tipoInstitucionRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_institucion_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function registrar(Request $request, InstitucionRepository $institucionRepository)
    {
        try {
            $institucion = new Institucion();
            $form = $this->createForm(InstitucionType::class, $institucion, ['action' => 'registrar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $institucion->setFechaFundacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['institucion']['fechaFundacion']));

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
    public function modificar(Request $request, Institucion $institucion, InstitucionRepository $tipoInstitucionRepository)
    {
        try {
            $form = $this->createForm(InstitucionType::class, $institucion, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tipoInstitucionRepository->edit($institucion);
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
     * @param User $tipoInstitucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function detail(Request $request, Institucion $tipoInstitucion)
    {
        return $this->render('modules/institucion/institucion/detail.html.twig', [
            'item' => $tipoInstitucion,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_institucion_eliminar", methods={"GET"})
     * @param Request $request
     * @param Institucion $tipoInstitucion
     * @param InstitucionRepository $tipoInstitucionRepository
     * @return Response
     */
    public function eliminar(Request $request, Institucion $tipoInstitucion, InstitucionRepository $tipoInstitucionRepository)
    {
        try {
            if ($tipoInstitucionRepository->find($tipoInstitucion) instanceof Institucion) {
                $tipoInstitucionRepository->remove($tipoInstitucion, true);
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
    public function asignarFacultad(Request $request, Institucion $institucion, InstitucionFacultadesRepository $institucionFacultadesRepository)
    {
        try {
            $entidad = new InstitucionFacultades();
            $form = $this->createForm(InstitucionFacultadesType::class, $entidad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exist = $institucionFacultadesRepository->findBy(['institucion' => $institucion->getId(), 'nombreFacultad' => $request->request->all()['institucion_facultades']['nombreFacultad']]);
                if (empty($exist)) {
                    $entidad->setInstitucion($institucion);
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
//        try {
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
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_institucion_asignar_sede', ['id' => $institucion->getId()], Response::HTTP_SEE_OTHER);
//        }
    }

    /**
     * @Route("/{id}/eliminar_sede", name="app_institucion_eliminar_sede", methods={"GET"})
     * @param Request $request
     * @param InstitucionFacultades $institucionSedes
     * @param InstitucionRepository $institucionSedesRepository
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




























}
