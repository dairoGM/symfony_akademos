<?php

namespace App\Controller\Postgrado;

use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Security\User;
use App\Form\Postgrado\SolicitudProgramaType;
use App\Repository\Postgrado\EstadoProgramaRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/postgrado/solicitud_programa")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class SolicitudProgramaController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_index", methods={"GET"})
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/postgrado/solicitud_programa/index.html.twig', [
            'registros' => $solicitudProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_solicitud_programa_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, SolicitudProgramaRepository $solicitudProgramaRepository, EstadoProgramaRepository $estadoProgramaRepository)
    {
        try {
            $solicitudPrograma = new SolicitudPrograma();
            $form = $this->createForm(SolicitudProgramaType::class, $solicitudPrograma, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if (!empty($_FILES['solicitud_programa']['name']['docPrograma'])) {
                    $file = $form['docPrograma']->getData();
                    $file_name = $_FILES['solicitud_programa']['name']['docPrograma'];
                    $solicitudPrograma->setDocPrograma($file_name);
                    $solicitudPrograma->setEstadoPrograma($estadoProgramaRepository->find(1));
                    $file->move("uploads/solicitud_programa", $file_name);
                }
                $solicitudProgramaRepository->add($solicitudPrograma, true);

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_solicitud_programa_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(SolicitudProgramaType::class, $solicitudPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if (!empty($_FILES['solicitud_programa']['name']['docPrograma'])) {
                    if ($solicitudPrograma->getDocPrograma() != null) {
                        if (file_exists('uploads/solicitud_programa/' . $solicitudPrograma->getDocPrograma())) {
                            unlink('uploads/solicitud_programa/' . $solicitudPrograma->getDocPrograma());
                        }
                    }
                    $file = $form['docPrograma']->getData();
                    $ext = explode('.', $_FILES['solicitud_programa']['name']['docPrograma']);
                    $file_name = uniqid() . '.' . end($ext);
                    $solicitudPrograma->setDocPrograma()($file_name);
                    $file->move("uploads/solicitud_programa", $file_name);
                }

                $solicitudProgramaRepository->edit($solicitudPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/postgrado/solicitud_programa/edit.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_modificar', ['id' => $solicitudPrograma->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_solicitud_programa_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function detail(Request $request, SolicitudPrograma $solicitudPrograma)
    {
        return $this->render('modules/postgrado/solicitud_programa/detail.html.twig', [
            'item' => $solicitudPrograma,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_solicitud_programa_eliminar", methods={"GET"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, SolicitudPrograma $solicitudPrograma, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            if ($solicitudProgramaRepository->find($solicitudPrograma) instanceof SolicitudPrograma) {
                $solicitudProgramaRepository->remove($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
