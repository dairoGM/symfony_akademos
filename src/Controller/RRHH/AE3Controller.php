<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\AE3;
use App\Form\RRHH\AE3Type;
use App\Repository\Personal\PersonaRepository;
use App\Repository\RRHH\AE3Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/rrhh/ae3")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE3")
 */
class AE3Controller extends AbstractController
{

    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/", name="app_rrhh_reporte_ae3_index", methods={"GET"})
     * @param AE3Repository $AE3Repository
     * @return Response
     */
    public function index(AE3Repository $AE3Repository, PersonaRepository $personaRepository)
    {
        $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
        $entidad = $persona[0]->getEntidad();
        return $this->render('modules/rrhh/reporte/ae3/index.html.twig', [
            'registros' => $AE3Repository->findBy(['entidad' => $entidad], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_rrhh_reporte_ae3_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param AE3Repository $aE3Repository
     * @return Response
     */
    public function registrar(Request $request, AE3Repository $aE3Repository, PersonaRepository $personaRepository)
    {
        try {
            $ae3 = new AE3();
            if (!$ae3->getMes()) {
                $ae3->setMes((int)date('n'));
            }
            if (!$ae3->getAnno()) {
                $ae3->setAnno((int)date('Y'));
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            if (isset($persona[0]) && $persona[0]->getEntidad()) {
                $ae3->setEntidad($persona[0]->getEntidad());
            }

            $form = $this->createForm(AE3Type::class, $ae3, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['ae3']['name']['documento'])) {
                    $file = $form['documento']->getData();
                    $file_name = $_FILES['ae3']['name']['documento'];
                    $ae3->setDocumento($file_name);
                    $file->move("uploads/rrhh/ae3/documento", $file_name);
                }

                $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
                if (isset($persona[0])) {
                    if (!$persona[0]->getEntidad()) {
                        $this->addFlash('error', 'La institucion no es correcta.');
                    }
                    if (empty($ae3->getEntidad())) {
                        $this->addFlash('error', 'La institucion no es correcta.');
                    }

                    $aE3Repository->add($ae3, true);
                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_rrhh_reporte_ae3_index', [], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'Persona no encontrada.');
            }


            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
            return $this->render('modules/rrhh/reporte/ae3/new.html.twig', [
                'form' => $form->createView(),
                'entidad' => $entidad
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae3_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/modificar", name="app_rrhh_reporte_ae3_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param AE3 $ae3
     * @param AE3Repository $ae3Repository
     * @return Response
     */
    public function modificar(Request $request, AE3 $ae3, AE3Repository $ae3Repository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(AE3Type::class, $ae3, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['ae3']['name']['documento'])) {
                    $file = $form['documento']->getData();
                    $file_name = $_FILES['ae3']['name']['documento'];
                    $ae3->setDocumento($file_name);
                    $file->move("uploads/rrhh/ae3/documento", $file_name);
                }
                if (empty($ae3->getEntidad())) {
                    $this->addFlash('error', 'La institucion no es correcta.');
                }
                $ae3Repository->edit($ae3);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_reporte_ae3_index', [], Response::HTTP_SEE_OTHER);
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
            return $this->render('modules/rrhh/reporte/ae3/edit.html.twig', [
                'form' => $form->createView(),
                'entidad' => $entidad,
                'ae3' => $ae3
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae3_modificar', ['id' => $ae3->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_rrhh_reporte_ae3_detail", methods={"GET", "POST"})
     * @param AE3 $ae3
     * @return Response
     * @throws ExceptionInterface
     */
    public function detail(AE3 $ae3)
    {
        $ae3Array = $this->normalizer->normalize($ae3, null, [
            'circular_reference_handler' => function ($object) {
                return method_exists($object, 'getId') ? $object->getId() : null;
            },
        ]);

        return $this->render('modules/rrhh/reporte/ae3/detail.html.twig', [
            'ae3' => $ae3Array,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_rrhh_reporte_ae3_eliminar", methods={"GET"})
     * @param Request $request
     * @param AE3 $ae3
     * @param AE3Repository $ae3Repository
     * @return Response
     */
    public function eliminar(Request $request, AE3 $ae3, AE3Repository $ae3Repository)
    {
        try {
            if ($ae3Repository->find($ae3) instanceof AE3) {
                $ae3Repository->remove($ae3, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_reporte_ae3_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rrhh_reporte_ae3_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae3_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}