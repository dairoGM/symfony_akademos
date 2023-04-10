<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Form\Pregrado\AprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\NoAprobarSolicitudProgramaAcademicoType;
use App\Form\Pregrado\SolicitudProgramaAcademicoType;
use App\Repository\Pregrado\EstadoProgramaAcademicoRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Services\Utils;
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
        $registros = $solicitudProgramaRepository->getSolicitudProgramaAcademicoAprobado([1,2,3, 4, 5, 7, 8]);
        return $this->render('modules/pregrado/solicitud_programa_academico/index.html.twig', [
            'registros' => $registros
//            'registros' => $solicitudProgramaRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_solicitud_programa_academico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function registrar(Request $request, Utils $utils, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository, EstadoProgramaAcademicoRepository $estadoProgramaAcademicoRepository)
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
                    $file->move("uploads/pregrado/solicitud_programa_academico/resolucion", $file_name);
                }
                if (!empty($_FILES['solicitud_programa_academico']['name']['fundamentacion'])) {
                    $file = $form['fundamentacion']->getData();
                    $file_name = $_FILES['solicitud_programa_academico']['name']['fundamentacion'];
                    $solicitudProgramaAcademico->setFundamentacion($file_name);
                    $file->move("uploads/pregrado/solicitud_programa_academico/fundamentacion", $file_name);
                }
                $solicitudProgramaAcademico->setEstadoProgramaAcademico($estadoProgramaAcademicoRepository->find(1));//Solicitado
                $solicitudProgramaRepository->add($solicitudProgramaAcademico, true);

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademico->getId(), 1);

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
                if (!empty($_FILES['solicitud_programa_academico']['name']['resolucion'])) {
                    $file = $form['resolucion']->getData();
                    $file_name = $_FILES['solicitud_programa_academico']['name']['resolucion'];
                    $solicitudPrograma->setResolucion($file_name);
                    $file->move("uploads/pregrado/solicitud_programa_academico/resolucion", $file_name);
                }
                if (!empty($_FILES['solicitud_programa_academico']['name']['fundamentacion'])) {
                    $file = $form['fundamentacion']->getData();
                    $file_name = $_FILES['solicitud_programa_academico']['name']['fundamentacion'];
                    $solicitudPrograma->setFundamentacion($file_name);
                    $file->move("uploads/pregrado/solicitud_programa_academico/fundamentacion", $file_name);
                }
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


    /**
     * @Route("/{id}/aprobar", name="app_solicitud_programa_academico_aprobar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaAcademicoRepository $estadoProgramaRepository
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function aprobar(Request $request, Utils $utils, EstadoProgramaAcademicoRepository $estadoProgramaRepository, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        try {
            $choices = [
                'cartaAprobacion' => empty($solicitudPrograma->getCartaAprobacion()) ? 'registrar' : 'modificar'
            ];

            $form = $this->createForm(AprobarSolicitudProgramaAcademicoType::class, $solicitudPrograma, $choices);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $solicitudPrograma->setFechaAprobacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['aprobar_solicitud_programa_academico']['fechaAprobacion']));
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaRepository->find(2));

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudPrograma->getId(), 2);

                if (!empty($_FILES['aprobar_solicitud_programa_academico']['name']['cartaAprobacion'])) {
                    if ($solicitudPrograma->getCartaAprobacion() != null) {
                        if (file_exists('uploads/pregrado/carta_aprobacion/' . $solicitudPrograma->getCartaAprobacion())) {
                            unlink('uploads/pregrado/carta_aprobacion/' . $solicitudPrograma->getCartaAprobacion());
                        }
                    }

                    $file = $form['cartaAprobacion']->getData();
                    $ext = explode('.', $_FILES['aprobar_solicitud_programa_academico']['name']['cartaAprobacion']);
                    $file_name = $_FILES['aprobar_solicitud_programa_academico']['name']['cartaAprobacion'];
                    $solicitudPrograma->setCartaAprobacion($file_name);
                    $file->move("uploads/pregrado/carta_aprobacion/", $file_name);
                }

                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico/aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/rechazar", name="app_solicitud_programa_academico_rechazar", methods={"GET", "POST"})
     * @param Request $request
     * @param EstadoProgramaAcademicoRepository $estadoProgramaRepository
     * @param SolicitudProgramaAcademico $solicitudPrograma
     * @param SolicitudProgramaAcademicoRepository $solicitudProgramaRepository
     * @return Response
     */
    public function rechazar(Request $request, Utils $utils, EstadoProgramaAcademicoRepository $estadoProgramaRepository, SolicitudProgramaAcademico $solicitudPrograma, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        try {
            $choices = [
                'dictamen' => empty($solicitudPrograma->getDictamen()) ? 'registrar' : 'modificar'
            ];
            $form = $this->createForm(NoAprobarSolicitudProgramaAcademicoType::class, $solicitudPrograma, $choices);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $solicitudPrograma->setEstadoProgramaAcademico($estadoProgramaRepository->find(3));

                $utils->guardarHistoricoEstadoProgramaAcademico($solicitudPrograma->getId(), 3);

                if (!empty($_FILES['no_aprobar_solicitud_programa_academico']['name']['dictamen'])) {
                    if ($solicitudPrograma->getDictamen() != null) {
                        if (file_exists('uploads/pregrado/dictamen/' . $solicitudPrograma->getDictamen())) {
                            unlink('uploads/pregrado/dictamen/' . $solicitudPrograma->getDictamen());
                        }
                    }

                    $file = $form['dictamen']->getData();
                    $ext = explode('.', $_FILES['no_aprobar_solicitud_programa_academico']['name']['dictamen']);
                    $file_name = $_FILES['no_aprobar_solicitud_programa_academico']['name']['dictamen'];
                    $solicitudPrograma->setDictamen($file_name);
                    $file->move("uploads/pregrado/dictamen/", $file_name);
                }


                $solicitudProgramaRepository->edit($solicitudPrograma, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/solicitud_programa_academico/no_aprobar.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_solicitud_programa_academico_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
