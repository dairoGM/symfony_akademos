<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\AE2;
use App\Entity\RRHH\CategoriaDocente;
use App\Entity\Security\User;
use App\Form\RRHH\AE2Type;
use App\Form\RRHH\CategoriaDocenteType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\RRHH\AE2Repository;
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
use function Symfony\Component\String\u;

/**
 * @Route("/rrhh/ae2")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE2")
 */
class AE2Controller extends AbstractController
{

    /**
     * @Route("/", name="app_rrhh_reporte_ae2_index", methods={"GET"})
     * @param AE2Repository $AE2Repository
     * @return Response
     */
    public function index(AE2Repository $AE2Repository, PersonaRepository $personaRepository)
    {
        $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
        $entidad = $persona[0]->getEntidad();
        return $this->render('modules/rrhh/reporte/ae2/index.html.twig', [
            'registros' => $AE2Repository->findBy(['entidad' => $entidad], ['id' => 'desc']),
        ]);
    }


    /**
     * @Route("/registrar", name="app_rrhh_reporte_ae2_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param AE2Repository $aE2Repository
     * @return Response
     */
    public function registrar(Request $request, AE2Repository $aE2Repository, EstructuraRepository $estructuraRepository, PersonaRepository $personaRepository, UserRepository $userRepository)
    {
        try {
            $ae2 = new AE2();
            if (!$ae2->getMes()) {
                $ae2->setMes((int)date('n'));
            }
            if (!$ae2->getAnno()) {
                $ae2->setAnno((int)date('Y'));
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            if (isset($persona[0]) && $persona[0]->getEntidad()) {
                $ae2->setEntidad($persona[0]->getEntidad());
            }

            $form = $this->createForm(AE2Type::class, $ae2, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['ae2']['name']['documento'])) {
                    $file = $form['documento']->getData();
                    $file_name = $_FILES['ae2']['name']['documento'];
                    $ae2->setDocumento($file_name);
                    $file->move("uploads/rrhh/ae2/documento", $file_name);
                }

                $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
                if (isset($persona[0])) {
                    if (!$persona[0]->getEntidad()) {
                        $this->addFlash('error', 'La institucion no es correcta.');
                    }

                    $aE2Repository->add($ae2, true);
                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'Persona no encontrada.');
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
            return $this->render('modules/rrhh/reporte/ae2/new.html.twig', [
                'form' => $form->createView(),
                'entidad' => $entidad
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae2_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_rrhh_reporte_ae2_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param AE2 $ae2
     * @param AE2Repository $ae2Repository
     * @return Response
     */
    public function modificar(Request $request, AE2 $ae2, AE2Repository $ae2Repository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(AE2Type::class, $ae2, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($_FILES['ae2']['name']['documento'])) {
                    $file = $form['resolucion']->getData();
                    $file_name = $_FILES['ae2']['name']['documento'];
                    $ae2->setDocumento($file_name);
                    $file->move("uploads/rrhh/ae2/documento", $file_name);
                }

                $ae2Repository->edit($ae2);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
            return $this->render('modules/rrhh/reporte/ae2/edit.html.twig', [
                'form' => $form->createView(),
                'entidad' => $entidad,
                'ae2' => $ae2
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae2_modificar', ['id' => $ae2->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_rrhh_reporte_ae2_detail", methods={"GET", "POST"})
     * @param AE2 $ae2
     * @return Response
     */
    public function detail(AE2 $ae2)
    {
        return $this->render('modules/rrhh/reporte/ae2/detail.html.twig', [
            'item' => $ae2,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_rrhh_reporte_ae2_eliminar", methods={"GET"})
     * @param Request $request
     * @param AE2 $ae2
     * @param AE2Repository $ae2Repository
     * @return Response
     */
    public function eliminar(Request $request, AE2 $ae2, AE2Repository $ae2Repository)
    {
        try {
            if ($ae2Repository->find($ae2) instanceof AE2) {
                $ae2Repository->remove($ae2, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/exportar_detalles", name="app_rrhh_reporte_ae2_exportar_detalle", methods={"GET"})
     */
    public function exportarExcel($id, AE2Repository $ae2Repository)
    {
        $ea2 = $ae2Repository->find($id);

        if (!$ea2) {
            throw new NotFoundHttpException('No se encontró el elemento AE2 con ID ' . $id);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $meses = [
            '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $entidad = $ea2->getEntidad();
        $nombreEntidad = $entidad ? $entidad->getNombre() : 'N/A';
        $mesTexto = $meses[$ea2->getMes()] ?? 'Mes inválido';

        $sheet->setCellValue('A1', 'Entidad:');
        $sheet->setCellValue('B1', $nombreEntidad);
        $sheet->setCellValue('A2', 'Mes:');
        $sheet->setCellValue('B2', $mesTexto);
        $sheet->setCellValue('A3', 'Año:');
        $sheet->setCellValue('B3', $ea2->getAnno());

        $data = [
            ['1.- Total Plantilla Aprobada', $ea2->getTotalPlantillaAprobada()],
            ['2.- Total Plantilla Cubierta', $ea2->getTotalPlantillaCubierta()],
            ['3.- Total General de Contratos (4+7+14)', $ea2->getTotalGeneralContratos()],
            ['4.- Total de Contratos de Profesores por tiempo determinado', $ea2->getTotalContratosProfesoresTiempoDeterminado()],
            ['5.- De ellos: a tiempo completo', $ea2->getProfesoresTiempoCompleto()],
            ['6.- Total de Contratos No Docentes (7+14)', $ea2->getTotalContratosNoDocentes()],
            ['7.- Contratos No Docentes con respaldo de plazas (8 a 13)', $ea2->getContratosNoDocentesConRespaldo()],
            ['8.- De ellos: por sustitución', $ea2->getContratosPorSustitucion()],
            ['9.- Período de Prueba', $ea2->getPeriodoPrueba()],
            ['10.- Serenos y Auxiliares de Limpieza', $ea2->getSerenosAuxiliaresLimpieza()],
            ['11.- Labores Agrícolas', $ea2->getLaboresAgricolas()],
            ['12.- Jubilados', $ea2->getJubilados()],
            ['13.- Otros', $ea2->getOtrosConRespaldo()],
            ['14.- Contratos No Docentes sin respaldo de plazas (15 a 19)', $ea2->getContratosNoDocentesSinRespaldo()],
            ['15.- De ellos: Serenos y Auxiliares de Limpieza', $ea2->getSerenosAuxiliaresLimpiezaSinRespaldo()],
            ['16.- Labores Agrícolas', $ea2->getLaboresAgricolasSinRespaldo()],
            ['17.- Jubilados', $ea2->getJubiladosSinRespaldo()],
            ['18.- Ejecución de Obra', $ea2->getEjecucionObra()],
            ['19.- Otros', $ea2->getOtrosSinRespaldo()],
            ['20.- Reserva Científica en Preparación', $ea2->getReservaCientificaPreparacion()],
            ['21.- Recién Graduados en Preparación (Nivel Sup.)', $ea2->getRecienGraduadosPreparacion()],
            ['22.- Reserva Dirección Provincial de Trabajo', $ea2->getReservaDireccionProvincialTrabajo()],
            ['23.- Técnicos Medios en Preparación', $ea2->getTecnicosMediosPreparacion()],
            ['24.- Estudiantes contratados por tiempo determinado', $ea2->getTotalEstudiantesUniversidadContratados()],
            ['25.- Estudiantes como Auxiliar Técnico de la Docencia', $ea2->getEstudiantesAuxiliaresTecnicosDocencia()],
            ['26.- Estudiantes en cargos No Docentes', $ea2->getEstudiantesCargosNoDocentes()],
            ['AE2 firmado', $ea2->getDocumento()],
        ];

        $row = 5;
        foreach ($data as $line) {
            $sheet->setCellValue("A$row", $line[0]);
            $sheet->setCellValue("B$row", $line[1]);
            $row++;
        }

        $writer = new Xls($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'reporte_ae2_id_' . $ea2->getId() . '.xls'
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="reporte_ae2_id_' . $ea2->getId() . '.xls"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Content-Disposition', $dispositionHeader);


        return $response;
    }

}
