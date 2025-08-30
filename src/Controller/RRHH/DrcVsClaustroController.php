<?php

namespace App\Controller\RRHH;

use App\Repository\RRHH\GrupoRepository;
use App\Form\RRHH\AE3Type;
use App\Repository\Personal\PersonaRepository;
use App\Repository\RRHH\AE3Repository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/rrhh/claustro_drc_pt")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_CLAUSTRO_DRC_PT")
 */
class DrcVsClaustroController extends AbstractController
{
    private ObjectNormalizer $normalizer;
    private EntityManagerInterface $entityManager;

    public function __construct(ObjectNormalizer $normalizer, EntityManagerInterface $entityManager)
    {
        $this->normalizer = $normalizer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_rrhh_reporte_drc_vs_claustro_index",  methods={"GET", "POST"})
     * @param Request $request
     * @param AE3Repository $ae3Repository
     * @param GrupoRepository $grupoRepository
     * @return Response
     * @throws ExceptionInterface
     */
    public function index(Request $request, AE3Repository $ae3Repository, GrupoRepository $grupoRepository): Response
    {
        $allPost = $request->request->all();

        // Manejo de filtros de mes
        if (isset($allPost['mes']) && !empty($allPost['mes'])) {
            $filMes = $allPost['mes'];
            $request->getSession()->set('fil_mes_tabla_drc_vs_claustro', $filMes);
        } else {
            $filMes = $request->getSession()->get('fil_mes_tabla_drc_vs_claustro', date('n'));
            $request->getSession()->set('fil_mes_tabla_drc_vs_claustro', $filMes);
        }

        // Manejo de filtros de año
        if (isset($allPost['anno']) && !empty($allPost['anno'])) {
            $filAnno = $allPost['anno'];
            $request->getSession()->set('fil_anno_tabla_drc_vs_claustro', $filAnno);
        } else {
            $filAnno = $request->getSession()->get('fil_anno_tabla_drc_vs_claustro', date('Y'));
            $request->getSession()->set('fil_anno_tabla_drc_vs_claustro', $filAnno);
        }

        $datos = [];

        // Obtener todos los grupos activos
        $grupos = $grupoRepository->findBy(['activo' => true]);

        // Crear un array para almacenar centros por grupo
        $centrosPorGrupo = [];

        // Inicializar array para centros sin grupo
        $centrosSinGrupo = [];

        $ae3 = $ae3Repository->getTabla1_4($filMes, $filAnno);

        if ($ae3) {
            foreach ($ae3 as $value) {
                $arrayData = $this->normalizer->normalize($value, null, [
                    'circular_reference_handler' => function ($object) {
                        return method_exists($object, 'getId') ? $object->getId() : null;
                    },
                ]);

                $entidad = $value->getEntidad();
                $totalesMasterDrc = $ae3Repository->getTotalDoctoresMasters($entidad->getId(), $filMes, $filAnno);

                $centroData = [
                    'nombre' => $arrayData['entidad']['siglas'] ?? 'Sin nombre',
                    'entidad_id' => $entidad->getId(),
                    'cuadros_docentes' => [
                        'pt' => ($arrayData['cuadrosDocentesPt'] ?? 0) + ($arrayData['profesoresTiempoCompletoPt'] ?? 0) + ($arrayData['asesoresMetodologosPt'] ?? 0),
                        'pa' => ($arrayData['cuadrosDocentesPa'] ?? 0) + ($arrayData['profesoresTiempoCompletoPa'] ?? 0) + ($arrayData['asesoresMetodologosPa'] ?? 0),
                        'as' => ($arrayData['cuadrosDocentesAs'] ?? 0) + ($arrayData['profesoresTiempoCompletoAs'] ?? 0) + ($arrayData['asesoresMetodologosAs'] ?? 0)
                    ],
                    'cuadros_investigacion' => [
                        'it' => ($arrayData['cuadrosInvestigacionIt'] ?? 0) + ($arrayData['investigadoresIt'] ?? 0),
                        'ia' => ($arrayData['cuadrosInvestigacionIa'] ?? 0) + ($arrayData['investigadoresIa'] ?? 0),
                        'iag' => ($arrayData['cuadrosInvestigacionIag'] ?? 0) + ($arrayData['investigadoresIag'] ?? 0),
                    ]
                ];

                $centroData['cuadros_docentes']['tot'] = $centroData['cuadros_docentes']['pt'] + $centroData['cuadros_docentes']['pa'] + $centroData['cuadros_docentes']['as'];
                $centroData['cuadros_investigacion']['tot'] = $centroData['cuadros_investigacion']['it'] + $centroData['cuadros_investigacion']['ia'] + $centroData['cuadros_investigacion']['iag'];

                $centroData['total_drc'] = $totalesMasterDrc[0]['total_dr'] ?? 0;
                $centroData['total_msc'] = $totalesMasterDrc[0]['total_msc'] ?? 0;

                $total = $centroData['cuadros_docentes']['tot'] + $centroData['cuadros_investigacion']['tot'];

                $centroData['porcentaje_drc'] = $total > 0 ? round(($centroData['total_drc'] / $total) * 100, 2) : 0;
                $centroData['porcentaje_msc'] = $total > 0 ? round(($centroData['total_msc'] / $total) * 100, 2) : 0;
                $centroData['total_general'] = $total;

                // Verificar a qué grupo pertenece esta entidad (relación ManyToOne)
                $grupoDeLaEntidad = $entidad->getGrupo();

                if ($grupoDeLaEntidad !== null) {
                    $grupoId = $grupoDeLaEntidad->getId();
                    if (!isset($centrosPorGrupo[$grupoId])) {
                        $centrosPorGrupo[$grupoId] = [
                            'grupo' => $grupoDeLaEntidad,
                            'centros' => []
                        ];
                    }
                    $centrosPorGrupo[$grupoId]['centros'][] = $centroData;
                } else {
                    // La entidad no pertenece a ningún grupo
                    $centrosSinGrupo[] = $centroData;
                }
            }

            // Agregar grupos con centros
            foreach ($centrosPorGrupo as $grupoData) {
                if (!empty($grupoData['centros'])) {
                    $datos[] = [
                        'nombre' => $grupoData['grupo']->getNombre(),
                        'grupo_id' => $grupoData['grupo']->getId(),
                        'centros' => $grupoData['centros']
                    ];
                }
            }

            // Agregar centros sin grupo como una categoría especial
            if (!empty($centrosSinGrupo)) {
                $datos[] = [
                    'nombre' => 'Sin Agrupación',
                    'grupo_id' => null,
                    'centros' => $centrosSinGrupo
                ];
            }
        }

        // Calcular subtotales para cada grupo
        foreach ($datos as &$grupo) {
            $grupo['subtotal'] = $this->calcularSubtotal($grupo['centros']);
        }
        unset($grupo);

        // Calcular total general
        $totalGeneral = $this->calcularTotalGeneral($datos);

        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');
        return $this->render('modules/rrhh/reporte/drc_vs_claustro/index.html.twig', [
            'grupos' => $datos,
            'totalGeneral' => $totalGeneral,
            'fecha' => new \DateTime(),
            'text_fil' => 'Año: ' . $filAnno . " | Mes: " .
                [
                    1 => 'Enero',
                    2 => 'Febrero',
                    3 => 'Marzo',
                    4 => 'Abril',
                    5 => 'Mayo',
                    6 => 'Junio',
                    7 => 'Julio',
                    8 => 'Agosto',
                    9 => 'Septiembre',
                    10 => 'Octubre',
                    11 => 'Noviembre',
                    12 => 'Diciembre'
                ][(int)$filMes] ?? 'Mes inválido',
            'fil_mes' => $filMes,
            'fil_anno' => $filAnno,
        ]);
    }

    private function calcularSubtotal(array $centros): array
    {
        $subtotal = [
            'cuadros_docentes' => ['pt' => 0, 'pa' => 0, 'as' => 0, 'tot' => 0],
            'cuadros_investigacion' => ['it' => 0, 'ia' => 0, 'iag' => 0, 'tot' => 0],
            'total_drc' => 0,
            'total_msc' => 0,
            'total_general' => 0,
            'porcentaje_drc' => 0,
            'porcentaje_msc' => 0
        ];

        foreach ($centros as $centro) {
            // Sumar valores individuales
            $subtotal['cuadros_docentes']['pt'] += $centro['cuadros_docentes']['pt'] ?? 0;
            $subtotal['cuadros_docentes']['pa'] += $centro['cuadros_docentes']['pa'] ?? 0;
            $subtotal['cuadros_docentes']['as'] += $centro['cuadros_docentes']['as'] ?? 0;

            $subtotal['cuadros_investigacion']['it'] += $centro['cuadros_investigacion']['it'] ?? 0;
            $subtotal['cuadros_investigacion']['ia'] += $centro['cuadros_investigacion']['ia'] ?? 0;
            $subtotal['cuadros_investigacion']['iag'] += $centro['cuadros_investigacion']['iag'] ?? 0;

            $subtotal['total_drc'] += $centro['total_drc'] ?? 0;
            $subtotal['total_msc'] += $centro['total_msc'] ?? 0;
            $subtotal['total_general'] += $centro['total_general'] ?? 0;
        }

        // Calcular totales
        $subtotal['cuadros_docentes']['tot'] = $subtotal['cuadros_docentes']['pt'] + $subtotal['cuadros_docentes']['pa'] + $subtotal['cuadros_docentes']['as'];
        $subtotal['cuadros_investigacion']['tot'] = $subtotal['cuadros_investigacion']['it'] + $subtotal['cuadros_investigacion']['ia'] + $subtotal['cuadros_investigacion']['iag'];

        // Calcular porcentajes CORRECTAMENTE: (total doctores / total general) * 100
        $subtotal['porcentaje_drc'] = $subtotal['total_general'] > 0 ?
            round(($subtotal['total_drc'] / $subtotal['total_general']) * 100, 2) : 0;

        $subtotal['porcentaje_msc'] = $subtotal['total_general'] > 0 ?
            round(($subtotal['total_msc'] / $subtotal['total_general']) * 100, 2) : 0;

        return $subtotal;
    }

    private function calcularTotalGeneral(array $grupos): array
    {
        $totalGeneral = [
            'cuadros_docentes' => ['pt' => 0, 'pa' => 0, 'as' => 0, 'tot' => 0],
            'cuadros_investigacion' => ['it' => 0, 'ia' => 0, 'iag' => 0, 'tot' => 0],
            'total_drc' => 0,
            'total_msc' => 0,
            'total_general' => 0,
            'porcentaje_drc' => 0,
            'porcentaje_msc' => 0
        ];

        foreach ($grupos as $grupo) {
            if (!isset($grupo['subtotal'])) {
                continue;
            }

            // Sumar valores de cada grupo
            $totalGeneral['cuadros_docentes']['pt'] += $grupo['subtotal']['cuadros_docentes']['pt'] ?? 0;
            $totalGeneral['cuadros_docentes']['pa'] += $grupo['subtotal']['cuadros_docentes']['pa'] ?? 0;
            $totalGeneral['cuadros_docentes']['as'] += $grupo['subtotal']['cuadros_docentes']['as'] ?? 0;

            $totalGeneral['cuadros_investigacion']['it'] += $grupo['subtotal']['cuadros_investigacion']['it'] ?? 0;
            $totalGeneral['cuadros_investigacion']['ia'] += $grupo['subtotal']['cuadros_investigacion']['ia'] ?? 0;
            $totalGeneral['cuadros_investigacion']['iag'] += $grupo['subtotal']['cuadros_investigacion']['iag'] ?? 0;

            $totalGeneral['total_drc'] += $grupo['subtotal']['total_drc'] ?? 0;
            $totalGeneral['total_msc'] += $grupo['subtotal']['total_msc'] ?? 0;
            $totalGeneral['total_general'] += $grupo['subtotal']['total_general'] ?? 0;
        }

        // Calcular totales
        $totalGeneral['cuadros_docentes']['tot'] = $totalGeneral['cuadros_docentes']['pt'] + $totalGeneral['cuadros_docentes']['pa'] + $totalGeneral['cuadros_docentes']['as'];
        $totalGeneral['cuadros_investigacion']['tot'] = $totalGeneral['cuadros_investigacion']['it'] + $totalGeneral['cuadros_investigacion']['ia'] + $totalGeneral['cuadros_investigacion']['iag'];

        // Calcular porcentajes CORRECTAMENTE: (total doctores / total general) * 100
        $totalGeneral['porcentaje_drc'] = $totalGeneral['total_general'] > 0 ?
            round(($totalGeneral['total_drc'] / $totalGeneral['total_general']) * 100, 2) : 0;

        $totalGeneral['porcentaje_msc'] = $totalGeneral['total_general'] > 0 ?
            round(($totalGeneral['total_msc'] / $totalGeneral['total_general']) * 100, 2) : 0;

        return $totalGeneral;
    }
}