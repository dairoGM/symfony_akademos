<?php

namespace App\Controller\Convenio;

use App\Entity\Convenio\Convenio;
use App\Entity\Convenio\ConvenioAccion;
use App\Entity\Personal\Persona;
use App\Form\Convenio\ConvenioAccionesType;
use App\Form\Convenio\ConvenioType;
use App\Repository\Convenio\ConvenioRepository;
use App\Repository\Convenio\ConvenioAccionRepository;
use App\Repository\Personal\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/convenio/convenio")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CONVENIO")
 */
class ConvenioController extends AbstractController
{

    /**
     * @Route("/", name="app_convenio_index", methods={"GET"})
     * @param ConvenioRepository $convenioRepository
     * @return Response
     */
    public function index(ConvenioRepository $convenioRepository)
    {
        return $this->render('modules/convenio/convenio/index.html.twig', [
            'registros' => $convenioRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_convenio_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ConvenioRepository $convenioRepository
     * @return Response
     */
    public function registrar(Request $request, ConvenioRepository $convenioRepository, PersonaRepository $personaRepository)
    {
        try {
            $entidad = new Convenio();
            $form = $this->createForm(ConvenioType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $entidad->setFechaSuscribe(\DateTime::createFromFormat('d/m/Y', $request->request->all()['convenio']['fechaSuscribe']));
                $entidad->setFechaCaducidad(\DateTime::createFromFormat('d/m/Y', $request->request->all()['convenio']['fechaCaducidad']));
                $entidad->setCreadoPor($personaRepository->findOneBy(['usuario' => $this->getUser()]));

                if (!empty($_FILES['convenio']['name']['documento'])) {
                    $file = $form['documento']->getData();
                    $file_name = $_FILES['convenio']['name']['documento'];
                    $entidad->setDocumento($file_name);
                    $file->move("uploads/convenio/intrumento_juridico/documento", $file_name);
                }

                $convenioRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_convenio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/convenio/convenio/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convenio_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_convenio_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Convenio $convenio
     * @param ConvenioRepository $convenioRepository
     * @return Response
     */
    public function modificar(Request $request, Convenio $convenio, ConvenioRepository $convenioRepository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(ConvenioType::class, $convenio, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['convenio']['fechaSuscribe']);
                $convenio->setFechaSuscribe(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['convenio']['fechaCaducidad']);
                $convenio->setFechaCaducidad(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));
                $convenio->setModificadoPor($personaRepository->findOneBy(['usuario' => $this->getUser()]));


                if (!empty($_FILES['ficha_salida']['name']['cartaInvitacion'])) {
                    if ($convenio->getDocumento() != null) {
                        if (file_exists('uploads/tramites/ficha_salida/carta_invitacion/' . $convenio->getDocumento())) {
                            unlink('uploads/convenio/intrumento_juridico/documento/' . $convenio->getDocumento());
                        }
                    }
                    $file = $form['cartaInvitacion']->getData();
                    $file_name = $_FILES['ficha_salida']['name']['cartaInvitacion'];
                    $convenio->setDocumento($file_name);
                    $file->move("uploads/convenio/intrumento_juridico/documento", $file_name);
                }


                $convenioRepository->edit($convenio);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_convenio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/convenio/convenio/edit.html.twig', [
                'form' => $form->createView(),
                'convenio' => $convenio
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convenio_index', ['id' => $convenio->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_convenio_detail", methods={"GET", "POST"})
     * @param Convenio $convenio
     * @param ConvenioAccionRepository $convenioAccionRepository
     * @return Response
     */
    public function detail(Convenio $convenio, ConvenioAccionRepository $convenioAccionRepository)
    {
        return $this->render('modules/convenio/convenio/detail.html.twig', [
            'item' => $convenio,
            'acciones' => $convenioAccionRepository->findBy(['convenio' => $convenio->getId()])
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_convenio_eliminar", methods={"GET"})
     * @param Request $request
     * @param Convenio $convenio
     * @param ConvenioRepository $convenioRepository
     * @return Response
     */
    public function eliminar(Request $request, Convenio $convenio, ConvenioRepository $convenioRepository)
    {
        try {
            if ($convenioRepository->find($convenio) instanceof Convenio) {
                $convenioRepository->remove($convenio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_convenio_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_convenio_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convenio_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/acciones", name="app_convenio_acciones", methods={"GET", "POST"})
     * @param Request $request
     * @param Convenio $convenio
     * @param ConvenioAccionRepository $convenioAccionRepository
     * @return Response
     */
    public function acciones(Request $request, Convenio $convenio, ConvenioAccionRepository $convenioAccionRepository)
    {
        try {
            $new = new ConvenioAccion();
            $form = $this->createForm(ConvenioAccionesType::class, $new, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $new->setConvenio($convenio);
                $convenioAccionRepository->add($new, true);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_convenio_acciones', ['id' => $convenio->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/convenio/convenio/acciones.html.twig', [
                'form' => $form->createView(),
                'convenio' => $convenio,
                'acciones' => $convenioAccionRepository->findBy(['convenio' => $convenio->getId()])
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convenio_acciones', ['id' => $convenio->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar_accion", name="app_convenio_eliminar_accion", methods={"GET"})
     * @param Request $request
     * @param ConvenioAccion $convenioAccion
     * @param ConvenioAccionRepository $convenioAccionRepository
     * @return Response
     */
    public function eliminarAccion(Request $request, ConvenioAccion $convenioAccion, ConvenioAccionRepository $convenioAccionRepository)
    {
        $id = $convenioAccion->getConvenio()->getId();
        try {
            if ($convenioAccionRepository->find($convenioAccion) instanceof ConvenioAccion) {
                $convenioAccionRepository->remove($convenioAccion, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_convenio_acciones', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_convenio_acciones', ['id' => $id], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convenio_acciones', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

    }

}
