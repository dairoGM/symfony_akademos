<?php

namespace App\Controller\Convenio;

use App\Entity\Convenio\Convenio;
use App\Form\Convenio\ConvenioType;
use App\Repository\Convenio\ConvenioRepository;
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
            'registros' => $convenioRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_convenio_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param ConvenioRepository $convenioRepository
     * @return Response
     */
    public function registrar(Request $request, ConvenioRepository $convenioRepository)
    {
        try {
            $entidad = new Convenio();
            $form = $this->createForm(ConvenioType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $entidad->setFechaSuscribe(\DateTime::createFromFormat('d/m/Y', $request->request->all()['convenio']['fechaSuscribe']));
                $entidad->setFechaCaducidad(\DateTime::createFromFormat('d/m/Y', $request->request->all()['convenio']['fechaCaducidad']));

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
    public function modificar(Request $request, Convenio $convenio, ConvenioRepository $convenioRepository)
    {
        try {
            $form = $this->createForm(ConvenioType::class, $convenio, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $temp = explode('/', $request->request->all()['convenio']['fechaSuscribe']);

                $convenio->setFechaSuscribe(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

                $temp = explode('/', $request->request->all()['convenio']['fechaCaducidad']);
                $convenio->setFechaCaducidad(new \DateTime($temp[2] . '/' . $temp[1] . '/' . $temp[0]));

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
            return $this->redirectToRoute('app_convenio_modificar', ['id' => $convenio->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_convenio_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Convenio $tipoPrograma
     * @return Response
     */
    public function detail(Request $request, Convenio $convenio)
    {
        return $this->render('modules/convenio/convenio/detail.html.twig', [
            'item' => $convenio,
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

}
