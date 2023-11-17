<?php

namespace App\Controller\Personal;


use App\Entity\Personal\Persona;
use App\Entity\Personal\PersonaOrganizacion;
use App\Entity\Personal\Plantilla;
use App\Entity\Personal\Responsable;
use App\Entity\Security\User;
use App\Entity\Traza\ConfiguracionTraza;
use App\Export\Personal\ExportListPersonaToPdf;
use App\Form\Personal\PersonaType;
use App\Form\Personal\PlantillaType;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Estructura\MunicipioRepository;
use App\Repository\Estructura\ResponsabilidadRepository;
use App\Repository\Personal\OrganizacionRepository;
use App\Repository\Personal\PersonaOrganizacionRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Personal\PlantillaRepository;
use App\Repository\Personal\ResponsableRepository;
use App\Repository\Personal\TipoOrganizacionRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Repository\Security\UserRepository;
use App\Services\DoctrineHelper;
use App\Services\HandlerFop;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/personal/persona")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_PERSON")
 */
class PersonaController extends AbstractController
{

    /**
     * @Route("/", name="app_persona_index", methods={"GET"})
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function index(Request $request, PersonaRepository $personaRepository, ResponsableRepository $responsableRepository, Utils $utils)
    {
        try {
            $request->getSession()->remove('usuario_modificado');

            $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
            $registros = $personaRepository->gePersonasDadoArrayEstructuras($estructurasNegocio);

            foreach ($registros as $value) {
                $resp = $responsableRepository->findBy(['persona' => $value->getId(), 'activo' => true]);
                $value->esResponsable = isset($resp[0]);
            }
            return $this->render('modules/personal/persona/index.html.twig', [
                'registros' => $registros,
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_persona_registrar", methods={"GET", "POST"})
     * @param TipoOrganizacionRepository $tipoOrganizacionRepository
     * @param OrganizacionRepository $organizacionRepository
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param PersonaRepository $personaRepository
     * @param MunicipioRepository $municipioRepository
     * @param EstructuraRepository $estructuraRepository
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return Response
     */
    public function registrar(TipoOrganizacionRepository $tipoOrganizacionRepository, OrganizacionRepository $organizacionRepository, EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder, PersonaRepository $personaRepository, MunicipioRepository $municipioRepository, EstructuraRepository $estructuraRepository, ResponsabilidadRepository $responsabilidadRepository)
    {
        $persona = new Persona();
        $choices = [
            'provincia_choices' => -1,
            'estructura_choices' => -1,
            'categoria_estructura_choices' => -1,
            'accion' => 'registrar'
        ];
        $form = $this->createForm(PersonaType::class, $persona, $choices);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!empty($form['foto']->getData())) {
                $file = $form['foto']->getData();
                $ext = $file->guessExtension();
                $file_name = md5(uniqid()) . "." . $ext;
                $persona->setFoto($file_name);
                $file->move("uploads/images/personas", $file_name);
            }

            if (!empty($request->request->all()['persona']['municipio'])) {
                $persona->setMunicipio($municipioRepository->find($request->request->all()['persona']['municipio']));
            }
            $persona->setFechaNacimiento(\DateTime::createFromFormat('d/m/Y', $request->request->all()['persona']['fechaNacimiento']));

            $estructura = $estructuraRepository->find($request->request->all()['persona']['estructura']);
            $persona->setEstructura($estructura);
            $responsabilidad = null;
            if (!empty($request->request->all()['persona']['responsabilidad'])) {
                $responsabilidad = $responsabilidadRepository->find($request->request->all()['persona']['responsabilidad']);
            }
            $persona->setResponsabilidad($responsabilidad);

            $usuario = new User();
            $usuario->setEmail($request->request->all()['persona']['usuario']);
            $encodedPassword = $encoder->encodePassword($usuario, $request->request->all()['persona']['contrasena']);
            $usuario->setPassword($encodedPassword);
            $usuario->setActivo(true);
            $usuario->setRole('ROLE_USER');
            $usuario->setPasswordChangeFirstTime(true);
            $em->persist($usuario);


            $persona->setUsuario($usuario);
            $personaRepository->add($persona, true);
            $em->persist($usuario);


            $plantilla = new Plantilla();
            $plantilla->setActivo(true);
            $plantilla->setPersona($persona);
            $plantilla->setEstructura($estructura);
            $plantilla->setResponsabilidad($responsabilidad);
            $em->persist($plantilla);

            if (isset($request->request->all()['persona']['organizacion']) && is_array($request->request->all()['persona']['organizacion'])) {
                foreach ($request->request->all()['persona']['organizacion'] as $value) {
                    $personaOrganizacion = new PersonaOrganizacion();
                    $personaOrganizacion->setPersona($persona);
                    $personaOrganizacion->setOrganizacion($organizacionRepository->find($value));
                    $em->persist($personaOrganizacion);
                }
            }

            $newConfiguracionTraza = new ConfiguracionTraza();
            $newConfiguracionTraza->setActivo(true);
            $newConfiguracionTraza->setPersona($persona);
            $em->persist($newConfiguracionTraza);

            $em->flush();

            $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('modules/personal/persona/new.html.twig', [
            'form' => $form->createView(),
            'organizaciones' => $organizacionRepository->findBy(['activo' => true], ['tipoOrganizacion' => 'asc', 'nombre' => 'asc']),
            'tipoOrganizacion' => $tipoOrganizacionRepository->findBy([], ['nombre' => 'asc'])
        ]);

    }


    /**
     * @Route("/{id}/modificar", name="app_persona_modificar", methods={"GET", "POST"})
     * @param TipoOrganizacionRepository $tipoOrganizacionRepository
     * @param OrganizacionRepository $organizacionRepository
     * @param UserRepository $userRepository
     * @param PlantillaRepository $plantillaRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param MunicipioRepository $municipioRepository
     * @param EstructuraRepository $estructuraRepository
     * @param ResponsabilidadRepository $responsabilidadRepository
     * @return Response
     */
    public function modificar(PersonaOrganizacionRepository $personaOrganizacionRepository, TipoOrganizacionRepository $tipoOrganizacionRepository,
                              OrganizacionRepository        $organizacionRepository, UserRepository $userRepository, PlantillaRepository $plantillaRepository,
                              UserPasswordEncoderInterface  $encoder, EntityManagerInterface $em, Request $request, Persona $persona, PersonaRepository $personaRepository,
                              MunicipioRepository           $municipioRepository, EstructuraRepository $estructuraRepository, ResponsabilidadRepository $responsabilidadRepository, Utils $utils, SolicitudProgramaAcademicoRepository $solicitudProgramaAcademicoRepository)
    {
        $choices = [
            'provincia_choices' => $persona->getProvincia()->getId(),
            'estructura_choices' => $persona->getEstructura()->getId(),
            'categoria_estructura_choices' => $persona->getCategoriaEstructura()->getId(),
            'accion' => 'editar'
        ];
        $form = $this->createForm(PersonaType::class, $persona, $choices);
        $form->handleRequest($request);
        $organizacionesAsociadas = $personaOrganizacionRepository->findBy(['persona' => $persona->getId()]);

        if ($form->isSubmitted()) {
            $email = $persona->getUsuario()->getEmail();
            if ($email != $request->request->all()['persona']['usuario']) {
                if ((empty($request->request->all()['persona']['contrasena'])) || empty($request->request->all()['persona']['contrasena2'])) {
                    $request->getSession()->set('usuario_modificado', $request->request->all()['persona']['usuario']);
                    $this->addFlash('error', 'Usted ha modificado el usuario, y requiere definir una nueva contraseña.');
                    return $this->redirectToRoute('app_persona_modificar', ['id' => $persona->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            if (!empty($form['foto']->getData())) {
                if ($persona->getFoto() != null) {
                    if (file_exists('uploads/images/personas/' . $persona->getFoto())) {
                        unlink('uploads/images/personas/' . $persona->getFoto());
                    }
                }

                $file = $form['foto']->getData();
                $ext = $file->guessExtension();
                $file_name = md5(uniqid()) . "." . $ext;
                $persona->setFoto($file_name);
                $file->move("uploads/images/personas", $file_name);
            }

            if ($email != $request->request->all()['persona']['usuario'] && (!empty($request->request->all()['persona']['contrasena'])) && !empty($request->request->all()['persona']['contrasena2'])) {
                $persona->getUsuario()->setEmail($request->request->all()['persona']['usuario']);
                $encodedPassword = $encoder->encodePassword($persona->getUsuario(), $request->request->all()['persona']['contrasena']);
                if ($request->request->all()['persona']['contrasena'] == $request->request->all()['persona']['contrasena2']) {
                    $persona->getUsuario()->setPassword($encodedPassword);
                }

                $em->persist($persona->getUsuario());

                $usuario = $userRepository->findOneBy(['email' => $email]);
                $usuario->setEmail($request->request->all()['persona']['usuario']);
                if ($request->request->all()['persona']['contrasena'] == $request->request->all()['persona']['contrasena2']) {
                    $usuario->setPassword($encodedPassword);
                }

                $em->persist($usuario);
                $em->flush();
            }

            if (!empty($request->request->all()['persona']['municipio'])) {
                $persona->setMunicipio($municipioRepository->find($request->request->all()['persona']['municipio']));
            }
            $persona->setFechaNacimiento(\DateTime::createFromFormat('d/m/Y', $request->request->all()['persona']['fechaNacimiento']));
            $estructura = $estructuraRepository->find($request->request->all()['persona']['estructura']);
            $persona->setEstructura($estructura);

            $responsabilidad = null;
            if (!empty($request->request->all()['persona']['responsabilidad'])) {
                $responsabilidad = $responsabilidadRepository->find($request->request->all()['persona']['responsabilidad']);
            }
            $persona->setResponsabilidad($responsabilidad);

            $personaRepository->edit($persona);


            if (!empty($request->request->all()['persona']['responsabilidad']) && $persona->getResponsabilidad()->getId() != $request->request->all()['persona']['responsabilidad'] || $persona->getEstructura()->getId()) {
                $plantillas = $plantillaRepository->findBy(['persona' => $persona]);
                foreach ($plantillas as $value) {
                    $value->setActivo(false);
                    $em->persist($value);
                }
                $plantilla = new Plantilla();
                $plantilla->setActivo(true);
                $plantilla->setPersona($persona);
                $plantilla->setEstructura($estructura);
                $plantilla->setResponsabilidad($responsabilidad);
                $em->persist($plantilla);
            }

            if (isset($request->request->all()['persona']['organizacion']) && is_array($request->request->all()['persona']['organizacion'])) {
                foreach ($organizacionesAsociadas as $value) {
                    $em->remove($value);
                }
                $em->flush();

                foreach ($request->request->all()['persona']['organizacion'] as $value) {
                    $personaOrganizacion = new PersonaOrganizacion();
                    $personaOrganizacion->setPersona($persona);
                    $personaOrganizacion->setOrganizacion($organizacionRepository->find($value));
                    $em->persist($personaOrganizacion);
                }
            }
            $em->flush();
            $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');


            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('modules/personal/persona/edit.html.twig', [
            'form' => $form->createView(),
            'persona' => $persona,
            'usuarioPersona' => $request->getSession()->has('usuario_modificado') ? $request->getSession()->get('usuario_modificado') : $persona->getUsuario()->getEmail(),
            'organizaciones' => $organizacionRepository->findBy(['activo' => true], ['tipoOrganizacion' => 'asc', 'nombre' => 'asc']),
            'tipoOrganizacion' => $tipoOrganizacionRepository->findBy([], ['nombre' => 'asc']),
            'organizacionesAsociadas' => $utils->procesarOrganizaciones($organizacionesAsociadas)
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_persona_eliminar", methods={"GET"})
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function eliminar(Persona $persona, PersonaRepository $personaRepository, PlantillaRepository $plantillaRepository)
    {
        try {
            if ($personaRepository->find($persona) instanceof Persona) {
                $plantilla = $plantillaRepository->findBy(['persona' => $persona->getId()]);
                if (is_array($plantilla)) {
                    foreach ($plantilla as $value) {
                        $plantillaRepository->remove($value, true);
                    }
                }

                $personaRepository->remove($persona, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');

                return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            pr($exception->getMessage());
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_persona_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $persona
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function detail(Request $request, Persona $persona, PlantillaRepository $plantillaRepository)
    {
        $plantillas = $plantillaRepository->findBy(['persona' => $persona], ['activo' => 'desc']);
        return $this->render('modules/personal/persona/detail.html.twig', [
            'item' => $persona,
            'plantillas' => $plantillas
        ]);
    }

    /**
     * @Route("/existe_ci", name="app_persona_existe_ci", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function existeCI(Request $request, PersonaRepository $personaRepository)
    {
        try {
            $exist = $personaRepository->findBy(['carnetIdentidad' => $request->request->get('carnetIdentidad')]);
            return $this->json(isset($exist[0]));
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/existe_serie_ci", name="app_persona_existe_serie_ci", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function existeSerieCI(Request $request, PersonaRepository $personaRepository)
    {
        try {
            $exist = $personaRepository->findBy(['numeroSerieCarnetIdentidad' => $request->request->get('numeroSerieCarnetIdentidad')]);
            return $this->json(isset($exist[0]));
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/existe_usuario", name="app_persona_existe_usuario", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function existeUsuario(Request $request, UserRepository $userRepository)
    {
        try {
            $exist = $userRepository->findBy(['email' => $request->request->get('email')]);
            return $this->json(isset($exist[0]));
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }


    /**
     * @Route("/{id}/modificar_estructura_responsabilidad", name="app_persona_modificar_estructura_responsabilidad", methods={"GET", "POST"})
     * @param PlantillaRepository $plantillaRepository
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Persona $persona
     * @return Response
     */
    public function modificarEstructuraResponsabiliadd(CategoriaEstructuraRepository $categoriaEstructuraRepository, PlantillaRepository $plantillaRepository, EntityManagerInterface $em, Request $request, Persona $persona, EstructuraRepository $estructuraRepository, ResponsabilidadRepository $responsabilidadRepository)
    {
        $plantilla = new Plantilla();
        $form = $this->createForm(PlantillaType::class, $plantilla);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($persona->getResponsabilidad()->getId() == $plantilla->getResponsabilidad()->getId() &&
                $persona->getEstructura()->getId() == $plantilla->getEstructura()->getId()) {
                $this->addFlash('error', 'La plantilla que intenta asignar ya existe.');
                return $this->redirectToRoute('app_persona_modificar_estructura_responsabilidad', ['id' => $persona->getId()], Response::HTTP_SEE_OTHER);
            }

            $plantillas = $plantillaRepository->findBy(['persona' => $persona]);
            foreach ($plantillas as $value) {
                $value->setActivo(false);
                $em->persist($value);
            }
            $plantilla->setPersona($persona);
            $plantillaRepository->add($plantilla, true);

            $categoriaEstructura = $categoriaEstructuraRepository->find($request->request->all()['plantilla']['categoriaEstructura']);
            $persona->setCategoriaEstructura($categoriaEstructura);
            $persona->setEstructura($plantilla->getEstructura());
            $persona->setResponsabilidad($plantilla->getResponsabilidad());
            $em->persist($persona);
            $em->flush();

            $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modules/personal/persona/edit_estructura_responsabilidad.html.twig', [
            'form' => $form->createView(),
            'persona' => $persona
        ]);
    }

    /**
     * @Route("/{id}/definir_responsable", name="app_persona_definir_responsable", methods={"GET"})
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param Responsable $responsableRepository
     * @return Response
     */
    public function definirResponsable(Request $request, Persona $persona, PersonaRepository $personaRepository, ResponsableRepository $responsableRepository)
    {
        try {
            if ($personaRepository->find($persona) instanceof Persona) {

                $responsable = new Responsable();
                $responsable->setPersona($persona);
                $responsable->setActivo(true);
                $responsableRepository->add($responsable, true);
                $this->addFlash('success', 'La pesona ha sido definida como responsable satisfactoriamente.');
                return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_persona_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/validar_usuario", name="app_persona_validar_usuario", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @return Response
     */
    public function validarUsuario(Request $request, PersonaRepository $personaRepository)
    {
        try {
            $valido = false;
            if (strpos($request->request->get('usuario'), '@mes.gob.cu') !== false) {
                $valido = true;
            }

            return $this->json($valido);
        } catch (\Exception $exception) {
            return $this->json(false);
        }
    }


    /**
     * @Route("/exportar_pdf", name="app_persona_exportar_pdf", methods={"GET", "POST"})
     * @param HandlerFop $handFop
     * @param PersonaRepository $personaRepository
     * @param Utils $utils
     * @return Response
     */
    public function exportarPdf(HandlerFop $handFop, PersonaRepository $personaRepository, Utils $utils)
    {
        $estructurasNegocio = $utils->procesarRolesUsuarioAutenticado($this->getUser()->getId());
        $export = $personaRepository->getExportarListado($estructurasNegocio);
        $export = DoctrineHelper::toArray($export);
        return $handFop->exportToPdf(new ExportListPersonaToPdf($export));
    }

}
