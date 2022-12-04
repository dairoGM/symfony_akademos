<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\EstadoProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Pregrado\SolicitudProgramaAcademicoType;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/pregrado/solicitud_programa_academico")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class SolicitudProgramaAcademicoController extends AbstractController
{

    /**
     * @Route("/", name="app_solicitud_programa_academico_index", methods={"GET"})
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function index(SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/pregrado/solicitud_programa_academico/index.html.twig', [
            'registros' => $solicitudProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_solicitud_programa_academico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
    {
        try {
            $solicitudProgramaAcademico = new SolicitudProgramaAcademico();
            $form = $this->createForm(SolicitudProgramaAcademicoType::class, $solicitudProgramaAcademico, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if (!empty($_FILES['solicitud_programa_academico']['name']['resolucion'])) {
                    $file = $form['resolucion']->getData();
                    $file_name = $_FILES['solicitud_programa_academico']['name']['resolucion'];
                    $solicitudProgramaAcademico->setResolucion($file_name);
                    $file->move("uploads/solicitud_programa_academico/resolucion", $file_name);
                }
                if (!empty($_FILES['solicitud_programa_academico']['name']['fundamentacion'])) {
                    $file = $form['fundamentacion']->getData();
                    $file_name = $_FILES['solicitud_programa_academico']['name']['fundamentacion'];
                    $solicitudProgramaAcademico->setFundamentacion($file_name);
                    $file->move("uploads/solicitud_programa_academico/fundamentacion", $file_name);
                }
                $solicitudProgramaAcademico->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(1));//Solicitado
                $solicitudProgramaRepository->add($solicitudProgramaAcademico, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico/new.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_solicitud_programa_academico_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        try {
            $form = $this->createForm(SolicitudProgramaAcademicoType::class, $solicitudPrograma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $solicitudProgramaRepository->edit($solicitudPrograma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico/edit.html.twig', [
                'form' => $form->createView(),
                'solicitudProgramaAcademico' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_modificar', ['id' => $solicitudPrograma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_solicitud_programa_academico_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @return Response
     */
    public function detail(Request $request, SolicitudProgramaAcademico $solicitudProgramaAcademico)
    {
        return $this->render('modules/pregrado/solicitud_programa_academico/detail.html.twig', [
            'item' => $solicitudProgramaAcademico,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_solicitud_programa_academico_eliminar", methods={"GET"})
     * @param Request $request
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        try {
            if ($solicitudProgramaRepository->find($solicitudPrograma) instanceof SolicitudProgramaAcademico) {
                $solicitudProgramaRepository->remove($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
