<?php

namespace App\Controller\Pregrado;

use App\Entity\Pregrado\ModificacionPlanEstudio;
use App\Entity\Pregrado\PlanEstudio;
use App\Entity\Security\User;
use App\Form\Pregrado\ModificacionPlanEstudioType;
use App\Form\Pregrado\PlanEstudioType;
use App\Repository\Pregrado\CursoAcademicoRepository;
use App\Repository\Pregrado\ModificacionPlanEstudioRepository;
use App\Repository\Pregrado\PlanEstudioRepository;
use Cassandra\Timestamp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/pregrado/plan_estudio")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CATDOC")
 */
class PlanEstudioController extends AbstractController
{

    /**
     * @Route("/", name="app_plan_estudio_index", methods={"GET"})
     * @param PlanEstudioRepository $planEstudioRepository
     * @return Response
     */
    public function index(PlanEstudioRepository $planEstudioRepository)
    {
        return $this->render('modules/pregrado/plan_estudio/index.html.twig', [
            'registros' => $planEstudioRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_plan_estudio_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param PlanEstudioRepository $planEstudioRepository
     * @return Response
     */
    public function registrar(Request $request, PlanEstudioRepository $planEstudioRepository, CursoAcademicoRepository $cursoAcademicoRepository)
    {
        try {
            $planEstudioEntity = new PlanEstudio();
            $form = $this->createForm(PlanEstudioType::class, $planEstudioEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $planEstudioEntity->setNombre('Plan de estudio ' . $cursoAcademicoRepository->find($request->request->all()['plan_estudio']['cursoAcademico'])->getNombre());
                $planEstudioEntity->setFechaAprobacion(\DateTime::createFromFormat('d/m/Y', $request->request->all()['plan_estudio']['fechaAprobacion']));

                if (!empty($_FILES['plan_estudio']['name']['planEstudio'])) {
                    $file = $form['planEstudio']->getData();
                    $file_name = $_FILES['plan_estudio']['name']['planEstudio'];
                    $planEstudioEntity->setPlanEstudio($file_name);
                    $file->move("uploads/plan_estudio/plan_estudio", $file_name);
                }

                $planEstudioRepository->add($planEstudioEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_estudio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/plan_estudio/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_estudio_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_plan_estudio_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param PlanEstudio $planEstudio
     * @param PlanEstudioRepository $planEstudioRepository
     * @return Response
     */
    public function modificar(Request $request, planEstudio $planEstudio, PlanEstudioRepository $planEstudioRepository)
    {
        try {
            $form = $this->createForm(PlanEstudioType::class, $planEstudio, ['action' => 'modificar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['plan_estudio']['fechaAprobacion']);
                $planEstudio->setFechaAprobacion(new \DateTime($temp[1] . '/' . $temp[0] . '/' . $temp[2]));

                if (!empty($_FILES['plan_estudio']['name']['planEstudio'])) {
                    if ($planEstudio->getPlanEstudio() != null) {
                        if (file_exists('uploads/plan_estudio/plan_estudio/' . $planEstudio->getPlanEstudio())) {
                            unlink('uploads/plan_estudio/plan_estudio/' . $planEstudio->getPlanEstudio());
                        }
                    }
                    $file = $form['planEstudio']->getData();
                    $ext = explode('.', $_FILES['plan_estudio']['name']['planEstudio']);
                    $file_name = uniqid() . '.' . end($ext);
                    $planEstudio->setPlanEstudio()($file_name);
                    $file->move("uploads/plan_estudio/plan_estudio", $file_name);
                }

                $planEstudioRepository->edit($planEstudio);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_estudio_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/plan_estudio/edit.html.twig', [
                'form' => $form->createView(),
                'planEstudio' => $planEstudio
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_estudio_modificar', ['id' => $planEstudio], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_plan_estudio_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param PlanEstudio $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, PlanEstudio $planEstudio)
    {
        return $this->render('modules/pregrado/plan_estudio/detail.html.twig', [
            'item' => $planEstudio,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_plan_estudio_eliminar", methods={"GET"})
     * @param planEstudio $planEstudio
     * @param PlanEstudioRepository $planEstudioRepository
     * @return Response
     */
    public function eliminar(PlanEstudio $planEstudio, PlanEstudioRepository $planEstudioRepository)
    {
        try {
            if ($planEstudioRepository->find($planEstudio) instanceof PlanEstudio) {
                $planEstudioRepository->remove($planEstudio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_estudio_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_plan_estudio_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_estudio_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/modificaciones", name="app_plan_estudio_modificaciones", methods={"GET", "POST"})
     * @param Request $request
     * @param PlanEstudio $planEstudio
     * @param ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository
     * @return Response
     */
    public function modificaciones(Request $request, planEstudio $planEstudio, ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository)
    {
//        try {
            $modificacion = new ModificacionPlanEstudio();
            $form = $this->createForm(ModificacionPlanEstudioType::class, $modificacion);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $temp = explode('/', $request->request->all()['modificacion_plan_estudio']['fechaAprobacion']);
                $modificacion->setFechaAprobacion(new \DateTime($temp[1] . '/' . $temp[0] . '/' . $temp[2]));
                $modificacion->setPlanEstudio($planEstudio);
                if (!empty($_FILES['modificacion_plan_estudio']['name']['planEstudioDoc'])) {
                    $file = $form['planEstudioDoc']->getData();
                    $file_name = $_FILES['modificacion_plan_estudio']['name']['planEstudioDoc'];
                    $modificacion->setPlanEstudioDoc($file_name);
                    $file->move("uploads/pregrado/plan_estudio/modificaciones/plan_estudio", $file_name);
                }
                if (!empty($_FILES['modificacion_plan_estudio']['name']['dictamen'])) {
                    $file = $form['dictamen']->getData();
                    $file_name = $_FILES['modificacion_plan_estudio']['name']['dictamen'];
                    $modificacion->setDictamen($file_name);
                    $file->move("uploads/pregrado/plan_estudio/modificaciones/dictamen", $file_name);
                }

                $modificacionPlanEstudioRepository->add($modificacion, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_estudio_modificaciones', ['id' => $planEstudio->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/pregrado/plan_estudio/modificaciones.html.twig', [
                'form' => $form->createView(),
                'planEstudio' => $planEstudio,
                'registros' => $modificacionPlanEstudioRepository->findBy(['planEstudio' => $planEstudio->getId()]),
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_plan_estudio_modificaciones', ['id' => $planEstudio->getId()], Response::HTTP_SEE_OTHER);
//        }
    }

    /**
     * @Route("/{id}/eliminar_modificacion", name="app_plan_estudio_eliminar_modificacion", methods={"GET"})
     * @param Request $request
     * @param ModificacionPlanEstudio $modificacionPlanEstudio
     * @param ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository
     * @return Response
     */
    public function eliminarModificacion(Request $request, ModificacionPlanEstudio $modificacionPlanEstudio, ModificacionPlanEstudioRepository $modificacionPlanEstudioRepository)
    {
        try {
            if ($modificacionPlanEstudio instanceof ModificacionPlanEstudio) {
                $modificacionPlanEstudioRepository->remove($modificacionPlanEstudio, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_plan_estudio_modificaciones', ['id' => $modificacionPlanEstudio->getPlanEstudio()->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_plan_estudio_modificaciones', ['id' => $modificacionPlanEstudio->getPlanEstudio()->getId()], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_plan_estudio_modificaciones', ['id' => $modificacionPlanEstudio->getPlanEstudio()->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
