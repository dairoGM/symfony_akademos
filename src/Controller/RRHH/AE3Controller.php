<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\AE3;
use App\Entity\RRHH\CategoriaDocente;
use App\Entity\Security\User;
use App\Form\RRHH\AE3Type;
use App\Form\RRHH\CategoriaDocenteType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\RRHH\AE3Repository;
use App\Repository\RRHH\CategoriaDocenteRepository;
use App\Repository\Security\UserRepository;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/rrhh/ae3")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE3")
 */
class AE3Controller extends AbstractController
{
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
//        try {
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
                    $file->move("uploads/rrhh/ae2/documento", $file_name);
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
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_rrhh_reporte_ae3_registrar', [], Response::HTTP_SEE_OTHER);
//        }
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
                    $this->addFlash('error', 'La institución no es correcta.');
                }

                // Recalcular totales
                $ae3->setTotalCuadros(
                    $ae3->getCuadrosDocentes() +
                    $ae3->getCuadrosAdministrativos() +
                    $ae3->getCuadrosInvestigacion() +
                    $ae3->getOtrosCuadros()
                );

                $ae3->setTotalTecnicos(
                    $ae3->getProfesoresTiempoCompleto() +
                    $ae3->getAsesoresMetodologos() +
                    $ae3->getInvestigadores() +
                    $ae3->getOtrosTecnicos()
                );

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
     */
    public function detail(AE3 $ae3)
    {
        return $this->render('modules/rrhh/reporte/ae3/detail.html.twig', [
            'item' => $ae3,
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