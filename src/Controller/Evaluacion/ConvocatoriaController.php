<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\Convocatoria;
use App\Entity\Security\User;
use App\Form\Evaluacion\ConvocatoriaType;
use App\Repository\Evaluacion\ConvocatoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evaluacion/convocatoria")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_CONVOCATORIA")
 */
class ConvocatoriaController extends AbstractController
{

    /**
     * @Route("/", name="app_convocatoria_index", methods={"GET"})
     * @param ConvocatoriaRepository $convocatoriaRepository
     * @return Response
     */
    public function index(ConvocatoriaRepository $convocatoriaRepository)
    {
        return $this->render('modules/evaluacion/convocatoria/index.html.twig', [
            'registros' => $convocatoriaRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_convocatoria_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ConvocatoriaRepository $convocatoriaRepository
     * @return Response
     */
    public function registrar(Request $request, ConvocatoriaRepository $convocatoriaRepository)
    {
        try {
            $convocatoria = new Convocatoria();
            $form = $this->createForm(ConvocatoriaType::class, $convocatoria, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if (!empty($request->request->all()['convocatoria']['fechaInicio'])) {
                    $convocatoria->setFechaInicio(\DateTime::createFromFormat('d/m/Y', $request->request->all()['convocatoria']['fechaInicio']));
                }
                if (!empty($request->request->all()['convocatoria']['fechaFin'])) {
                    $convocatoria->setFechaFin(\DateTime::createFromFormat('d/m/Y', $request->request->all()['convocatoria']['fechaFin']));
                }

                if (!empty($_FILES['convocatoria']['name']['carta'])) {
                    $file = $form['carta']->getData();
                    $file_name = $_FILES['convocatoria']['name']['carta'];
                    $convocatoria->setCarta($file_name);
                    $file->move("uploads/evaluacion/convocatoria/carta", $file_name);
                }

                $convocatoriaRepository->add($convocatoria, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/convocatoria/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_convocatoria_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Convocatoria $convocatoria
     * @param ConvocatoriaRepository $convocatoriaRepository
     * @return Response
     */
    public function modificar(Request $request, Convocatoria $convocatoria, ConvocatoriaRepository $convocatoriaRepository)
    {
        try {
            $form = $this->createForm(ConvocatoriaType::class, $convocatoria, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $temp = explode('/', $request->request->all()['convocatoria']['fechaInicio']);
                $convocatoria->setFechaInicio(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['convocatoria']['fechaFin']);
                $convocatoria->setFechaFin(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $convocatoriaRepository->edit($convocatoria);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/convocatoria/edit.html.twig', [
                'form' => $form->createView(),
                'convocatoria' => $convocatoria
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_convocatoria_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Convocatoria $convocatoria
     * @return Response
     */
    public function detail(Request $request, Convocatoria $convocatoria)
    {
        return $this->render('modules/evaluacion/convocatoria/detail.html.twig', [
            'item' => $convocatoria,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_convocatoria_eliminar", methods={"GET"})
     * @param Request $request
     * @param Convocatoria $tipoPrograma
     * @param ConvocatoriaRepository $tipoProgramaRepository
     * @return Response
     */
    public function eliminar(Request $request, Convocatoria $tipoPrograma, ConvocatoriaRepository $tipoProgramaRepository)
    {
        try {
            if ($tipoProgramaRepository->find($tipoPrograma) instanceof Convocatoria) {
                $tipoProgramaRepository->remove($tipoPrograma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_convocatoria_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
