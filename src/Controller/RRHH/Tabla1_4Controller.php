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
 * @Route("/rrhh/tabla1-4")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_1_4")
 */
class Tabla1_4Controller extends AbstractController
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }


    /**
     * @Route("/", name="app_rrhh_reporte_1_4_index",  methods={"GET", "POST"})
     * @param AE3Repository $AE3Repository
     * @return Response
     */
    public function index(Request $request, AE3Repository $ae3Repository)
    {
        $allPost = $request->request->all();

        if (isset($allPost['mes']) && !empty($allPost['mes'])) {
            $filMes = $allPost['mes'];
            $request->getSession()->set('fil_mes_tabla_1_4', $filMes);
        } else {
            if (!$request->getSession()->has('fil_mes_tabla_1_4')) {
                $filMes = date('n');
                $request->getSession()->set('fil_mes_tabla_1_4', $filMes);
            }

        }
        if (isset($allPost['anno']) && !empty($allPost['anno'])) {
            $filMes = $allPost['anno'];
            $request->getSession()->set('fil_anno_tabla_1_4', $filMes);
        } else {
            if (!$request->getSession()->has('fil_anno_tabla_1_4')) {
                $filAnno = date('Y');
                $request->getSession()->set('fil_anno_tabla_1_4', $filAnno);
            }
        }

        $datos = [];
        $centrosG1 = [];
        $centrosG2 = [];
        $centrosG3 = [];
        $centrosG4 = [];
        $ae3 = $ae3Repository->getTabla1_4($request->getSession()->get('fil_mes_tabla_1_4'), $request->getSession()->get('fil_anno_tabla_1_4'));
        if ($ae3) {
            foreach ($ae3 as $value) {
                $ae3Array = $this->normalizer->normalize($value, null, [
                    'circular_reference_handler' => function ($object) {
                        return method_exists($object, 'getId') ? $object->getId() : null;
                    },
                ]);
                $categoriaEstrutura = $value->getEntidad()->getCategoriaEstructura()->getId();
                $totalCuadrosDocentes = (($ae3Array['cuadrosDocentesPt'] ?? 0) + ($ae3Array['cuadrosDocentesPa'] ?? 0) + ($ae3Array['cuadrosDocentesAs'] ?? 0) + ($ae3Array['cuadrosDocentesI'] ?? 0) + ($ae3Array['cuadrosDocentesAuxTecDoc'] ?? 0));
                $totalCuadrosInvest = (($ae3Array['cuadrosInvestigacionIt'] ?? 0) + ($ae3Array['cuadrosInvestigacionIa'] ?? 0) + ($ae3Array['cuadrosInvestigacionIag'] ?? 0) + ($ae3Array['cuadrosInvestigacionAi'] ?? 0));
                $totalProfCompleto = (($ae3Array['profesoresTiempoCompletoPt'] ?? 0) + ($ae3Array['profesoresTiempoCompletoPa'] ?? 0) + ($ae3Array['profesoresTiempoCompletoAs'] ?? 0) + ($ae3Array['profesoresTiempoCompletoI'] ?? 0) + ($ae3Array['profesoresTiempoCompletoAuxTecDoc'] ?? 0));
                $totalAsesores = (($ae3Array['asesoresMetodologosPt'] ?? 0) + ($ae3Array['asesoresMetodologosPa'] ?? 0) + ($ae3Array['asesoresMetodologosAs'] ?? 0) + ($ae3Array['asesoresMetodologosI'] ?? 0));
                $totalInves = (($ae3Array['investigadoresIt'] ?? 0) + ($ae3Array['investigadoresIa'] ?? 0) + ($ae3Array['investigadoresIag'] ?? 0) + ($ae3Array['investigadoresAi'] ?? 0));

                $claustro = ($totalCuadrosDocentes + $totalCuadrosInvest + $totalProfCompleto + $totalAsesores + $totalInves);
                if (in_array($categoriaEstrutura, [5, 6, 8])) {
                    $centrosG1[] =
                        [
                            'nombre' => $ae3Array['entidad']['siglas'] ?? null,
                            'cuadros_docentes' => [
                                'tot' => $totalCuadrosDocentes,
                                'pt' => $ae3Array['cuadrosDocentesPt'] ?? 0,
                                'pa' => $ae3Array['cuadrosDocentesPa'] ?? 0,
                                'as' => $ae3Array['cuadrosDocentesAs'] ?? 0,
                                'i' => $ae3Array['cuadrosDocentesI'] ?? 0,
                                'atd' => $ae3Array['cuadrosDocentesAuxTecDoc'] ?? 0,
                            ],
                            'cuadros_investigacion' => [
                                'tot' => $totalCuadrosInvest,
                                'it' => $ae3Array['cuadrosInvestigacionIt'] ?? 0,
                                'ia' => $ae3Array['cuadrosInvestigacionIa'] ?? 0,
                                'iag' => $ae3Array['cuadrosInvestigacionIag'] ?? 0,
                                'ai' => $ae3Array['cuadrosInvestigacionAi'] ?? 0,
                            ],
                            'profesores_completo' => [
                                'tot' => $totalProfCompleto,
                                'pt' => $ae3Array['profesoresTiempoCompletoPt'] ?? 0,
                                'pa' => $ae3Array['profesoresTiempoCompletoPa'] ?? 0,
                                'as' => $ae3Array['profesoresTiempoCompletoAs'] ?? 0,
                                'i' => $ae3Array['profesoresTiempoCompletoI'] ?? 0,
                                'atd' => $ae3Array['profesoresTiempoCompletoAuxTecDoc'] ?? 0,
                            ],
                            'asesores' => [
                                'tot' => $totalAsesores,
                                'pt' => $ae3Array['asesoresMetodologosPt'] ?? 0,
                                'pa' => $ae3Array['asesoresMetodologosPa'] ?? 0,
                                'as' => $ae3Array['asesoresMetodologosAs'] ?? 0,
                                'i' => $ae3Array['asesoresMetodologosI'] ?? 0,
                            ],
                            'investigadores' => [
                                'tot' => $totalInves,
                                'it' => $ae3Array['investigadoresIt'] ?? 0,
                                'ia' => $ae3Array['investigadoresIa'] ?? 0,
                                'iag' => $ae3Array['investigadoresIag'] ?? 0,
                                'ai' => $ae3Array['investigadoresAi'] ?? 0,
                            ],
                            'otros' => [
                                'otros_cuadros' => (
                                    ($ae3Array['otrosCuadrosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAs'] ?? 0) +
                                    ($ae3Array['otrosCuadrosI'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIag'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAi'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosMsc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDr'] ?? 0)
                                ),
                                'claustro' => $claustro,
                                'tecnicos' => (
                                    ($ae3Array['otrosTecnicosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAs'] ?? 0) +
                                    ($ae3Array['otrosTecnicosI'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIag'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAi'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosMsc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDr'] ?? 0)
                                ),
                                'administrativos' => (
                                    ($ae3Array['administrativosTotalCubierta'] ?? 0) +
                                    ($ae3Array['administrativosDeEllosFem'] ?? 0) +
                                    ($ae3Array['administrativosJovenesTotal'] ?? 0) +
                                    ($ae3Array['administrativosJovenesFem'] ?? 0) +
                                    ($ae3Array['administrativosFem'] ?? 0) +
                                    ($ae3Array['administrativosPt'] ?? 0) +
                                    ($ae3Array['administrativosPa'] ?? 0) +
                                    ($ae3Array['administrativosAs'] ?? 0) +
                                    ($ae3Array['administrativosI'] ?? 0) +
                                    ($ae3Array['administrativosIt'] ?? 0) +
                                    ($ae3Array['administrativosIa'] ?? 0) +
                                    ($ae3Array['administrativosIag'] ?? 0) +
                                    ($ae3Array['administrativosAi'] ?? 0) +
                                    ($ae3Array['administrativosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['administrativosMsc'] ?? 0) +
                                    ($ae3Array['administrativosDr'] ?? 0)
                                ),
                                'servicio' => (
                                    ($ae3Array['servicioTotalCubierta'] ?? 0) +
                                    ($ae3Array['servicioDeEllosFem'] ?? 0) +
                                    ($ae3Array['servicioJovenesTotal'] ?? 0) +
                                    ($ae3Array['servicioJovenesFem'] ?? 0) +
                                    ($ae3Array['servicioFem'] ?? 0) +
                                    ($ae3Array['servicioPt'] ?? 0) +
                                    ($ae3Array['servicioPa'] ?? 0) +
                                    ($ae3Array['servicioAs'] ?? 0) +
                                    ($ae3Array['servicioI'] ?? 0) +
                                    ($ae3Array['servicioIt'] ?? 0) +
                                    ($ae3Array['servicioIa'] ?? 0) +
                                    ($ae3Array['servicioIag'] ?? 0) +
                                    ($ae3Array['servicioAi'] ?? 0) +
                                    ($ae3Array['servicioAuxTecDoc'] ?? 0) +
                                    ($ae3Array['servicioMsc'] ?? 0) +
                                    ($ae3Array['servicioDr'] ?? 0)
                                ),
                                'operarios' => (
                                    ($ae3Array['operariosTotalCubierta'] ?? 0) +
                                    ($ae3Array['operariosDeEllosFem'] ?? 0) +
                                    ($ae3Array['operariosJovenesTotal'] ?? 0) +
                                    ($ae3Array['operariosJovenesFem'] ?? 0) +
                                    ($ae3Array['operariosFem'] ?? 0) +
                                    ($ae3Array['operariosPt'] ?? 0) +
                                    ($ae3Array['operariosPa'] ?? 0) +
                                    ($ae3Array['operariosAs'] ?? 0) +
                                    ($ae3Array['operariosI'] ?? 0) +
                                    ($ae3Array['operariosIt'] ?? 0) +
                                    ($ae3Array['operariosIa'] ?? 0) +
                                    ($ae3Array['operariosIag'] ?? 0) +
                                    ($ae3Array['operariosAi'] ?? 0) +
                                    ($ae3Array['operariosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['operariosMsc'] ?? 0) +
                                    ($ae3Array['operariosDr'] ?? 0)
                                )
                            ]
                        ];
                }
                if (in_array($categoriaEstrutura, [24])) {
                    $centrosG2[] =
                        [
                            'nombre' => $ae3Array['entidad']['siglas'] ?? null,
                            'cuadros_docentes' => [
                                'tot' => $totalCuadrosDocentes,
                                'pt' => $ae3Array['cuadrosDocentesPt'] ?? 0,
                                'pa' => $ae3Array['cuadrosDocentesPa'] ?? 0,
                                'as' => $ae3Array['cuadrosDocentesAs'] ?? 0,
                                'i' => $ae3Array['cuadrosDocentesI'] ?? 0,
                                'atd' => $ae3Array['cuadrosDocentesAuxTecDoc'] ?? 0,
                            ],
                            'cuadros_investigacion' => [
                                'tot' => $totalCuadrosInvest,
                                'it' => $ae3Array['cuadrosInvestigacionIt'] ?? 0,
                                'ia' => $ae3Array['cuadrosInvestigacionIa'] ?? 0,
                                'iag' => $ae3Array['cuadrosInvestigacionIag'] ?? 0,
                                'ai' => $ae3Array['cuadrosInvestigacionAi'] ?? 0,
                            ],
                            'profesores_completo' => [
                                'tot' => $totalProfCompleto,
                                'pt' => $ae3Array['profesoresTiempoCompletoPt'] ?? 0,
                                'pa' => $ae3Array['profesoresTiempoCompletoPa'] ?? 0,
                                'as' => $ae3Array['profesoresTiempoCompletoAs'] ?? 0,
                                'i' => $ae3Array['profesoresTiempoCompletoI'] ?? 0,
                                'atd' => $ae3Array['profesoresTiempoCompletoAuxTecDoc'] ?? 0,
                            ],
                            'asesores' => [
                                'tot' => $totalAsesores,
                                'pt' => $ae3Array['asesoresMetodologosPt'] ?? 0,
                                'pa' => $ae3Array['asesoresMetodologosPa'] ?? 0,
                                'as' => $ae3Array['asesoresMetodologosAs'] ?? 0,
                                'i' => $ae3Array['asesoresMetodologosI'] ?? 0,
                            ],
                            'investigadores' => [
                                'tot' => $totalInves,
                                'it' => $ae3Array['investigadoresIt'] ?? 0,
                                'ia' => $ae3Array['investigadoresIa'] ?? 0,
                                'iag' => $ae3Array['investigadoresIag'] ?? 0,
                                'ai' => $ae3Array['investigadoresAi'] ?? 0,
                            ],
                            'otros' => [
                                'otros_cuadros' => (
                                    ($ae3Array['otrosCuadrosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAs'] ?? 0) +
                                    ($ae3Array['otrosCuadrosI'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIag'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAi'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosMsc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDr'] ?? 0)
                                ),
                                'claustro' => $claustro,
                                'tecnicos' => (
                                    ($ae3Array['otrosTecnicosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAs'] ?? 0) +
                                    ($ae3Array['otrosTecnicosI'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIag'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAi'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosMsc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDr'] ?? 0)
                                ),
                                'administrativos' => (
                                    ($ae3Array['administrativosTotalCubierta'] ?? 0) +
                                    ($ae3Array['administrativosDeEllosFem'] ?? 0) +
                                    ($ae3Array['administrativosJovenesTotal'] ?? 0) +
                                    ($ae3Array['administrativosJovenesFem'] ?? 0) +
                                    ($ae3Array['administrativosFem'] ?? 0) +
                                    ($ae3Array['administrativosPt'] ?? 0) +
                                    ($ae3Array['administrativosPa'] ?? 0) +
                                    ($ae3Array['administrativosAs'] ?? 0) +
                                    ($ae3Array['administrativosI'] ?? 0) +
                                    ($ae3Array['administrativosIt'] ?? 0) +
                                    ($ae3Array['administrativosIa'] ?? 0) +
                                    ($ae3Array['administrativosIag'] ?? 0) +
                                    ($ae3Array['administrativosAi'] ?? 0) +
                                    ($ae3Array['administrativosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['administrativosMsc'] ?? 0) +
                                    ($ae3Array['administrativosDr'] ?? 0)
                                ),
                                'servicio' => (
                                    ($ae3Array['servicioTotalCubierta'] ?? 0) +
                                    ($ae3Array['servicioDeEllosFem'] ?? 0) +
                                    ($ae3Array['servicioJovenesTotal'] ?? 0) +
                                    ($ae3Array['servicioJovenesFem'] ?? 0) +
                                    ($ae3Array['servicioFem'] ?? 0) +
                                    ($ae3Array['servicioPt'] ?? 0) +
                                    ($ae3Array['servicioPa'] ?? 0) +
                                    ($ae3Array['servicioAs'] ?? 0) +
                                    ($ae3Array['servicioI'] ?? 0) +
                                    ($ae3Array['servicioIt'] ?? 0) +
                                    ($ae3Array['servicioIa'] ?? 0) +
                                    ($ae3Array['servicioIag'] ?? 0) +
                                    ($ae3Array['servicioAi'] ?? 0) +
                                    ($ae3Array['servicioAuxTecDoc'] ?? 0) +
                                    ($ae3Array['servicioMsc'] ?? 0) +
                                    ($ae3Array['servicioDr'] ?? 0)
                                ),
                                'operarios' => (
                                    ($ae3Array['operariosTotalCubierta'] ?? 0) +
                                    ($ae3Array['operariosDeEllosFem'] ?? 0) +
                                    ($ae3Array['operariosJovenesTotal'] ?? 0) +
                                    ($ae3Array['operariosJovenesFem'] ?? 0) +
                                    ($ae3Array['operariosFem'] ?? 0) +
                                    ($ae3Array['operariosPt'] ?? 0) +
                                    ($ae3Array['operariosPa'] ?? 0) +
                                    ($ae3Array['operariosAs'] ?? 0) +
                                    ($ae3Array['operariosI'] ?? 0) +
                                    ($ae3Array['operariosIt'] ?? 0) +
                                    ($ae3Array['operariosIa'] ?? 0) +
                                    ($ae3Array['operariosIag'] ?? 0) +
                                    ($ae3Array['operariosAi'] ?? 0) +
                                    ($ae3Array['operariosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['operariosMsc'] ?? 0) +
                                    ($ae3Array['operariosDr'] ?? 0)
                                )
                            ]
                        ];
                }
                if (in_array($categoriaEstrutura, [7])) {
                    $centrosG3[] =
                        [
                            'nombre' => $ae3Array['entidad']['siglas'] ?? null,
                            'cuadros_docentes' => [
                                'tot' => $totalCuadrosDocentes,
                                'pt' => $ae3Array['cuadrosDocentesPt'] ?? 0,
                                'pa' => $ae3Array['cuadrosDocentesPa'] ?? 0,
                                'as' => $ae3Array['cuadrosDocentesAs'] ?? 0,
                                'i' => $ae3Array['cuadrosDocentesI'] ?? 0,
                                'atd' => $ae3Array['cuadrosDocentesAuxTecDoc'] ?? 0,
                            ],
                            'cuadros_investigacion' => [
                                'tot' => $totalCuadrosInvest,
                                'it' => $ae3Array['cuadrosInvestigacionIt'] ?? 0,
                                'ia' => $ae3Array['cuadrosInvestigacionIa'] ?? 0,
                                'iag' => $ae3Array['cuadrosInvestigacionIag'] ?? 0,
                                'ai' => $ae3Array['cuadrosInvestigacionAi'] ?? 0,
                            ],
                            'profesores_completo' => [
                                'tot' => $totalProfCompleto,
                                'pt' => $ae3Array['profesoresTiempoCompletoPt'] ?? 0,
                                'pa' => $ae3Array['profesoresTiempoCompletoPa'] ?? 0,
                                'as' => $ae3Array['profesoresTiempoCompletoAs'] ?? 0,
                                'i' => $ae3Array['profesoresTiempoCompletoI'] ?? 0,
                                'atd' => $ae3Array['profesoresTiempoCompletoAuxTecDoc'] ?? 0,
                            ],
                            'asesores' => [
                                'tot' => $totalAsesores,
                                'pt' => $ae3Array['asesoresMetodologosPt'] ?? 0,
                                'pa' => $ae3Array['asesoresMetodologosPa'] ?? 0,
                                'as' => $ae3Array['asesoresMetodologosAs'] ?? 0,
                                'i' => $ae3Array['asesoresMetodologosI'] ?? 0,
                            ],
                            'investigadores' => [
                                'tot' => $totalInves,
                                'it' => $ae3Array['investigadoresIt'] ?? 0,
                                'ia' => $ae3Array['investigadoresIa'] ?? 0,
                                'iag' => $ae3Array['investigadoresIag'] ?? 0,
                                'ai' => $ae3Array['investigadoresAi'] ?? 0,
                            ],
                            'otros' => [
                                'otros_cuadros' => (
                                    ($ae3Array['otrosCuadrosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAs'] ?? 0) +
                                    ($ae3Array['otrosCuadrosI'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIag'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAi'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosMsc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDr'] ?? 0)
                                ),
                                'claustro' => $claustro,
                                'tecnicos' => (
                                    ($ae3Array['otrosTecnicosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAs'] ?? 0) +
                                    ($ae3Array['otrosTecnicosI'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIag'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAi'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosMsc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDr'] ?? 0)
                                ),
                                'administrativos' => (
                                    ($ae3Array['administrativosTotalCubierta'] ?? 0) +
                                    ($ae3Array['administrativosDeEllosFem'] ?? 0) +
                                    ($ae3Array['administrativosJovenesTotal'] ?? 0) +
                                    ($ae3Array['administrativosJovenesFem'] ?? 0) +
                                    ($ae3Array['administrativosFem'] ?? 0) +
                                    ($ae3Array['administrativosPt'] ?? 0) +
                                    ($ae3Array['administrativosPa'] ?? 0) +
                                    ($ae3Array['administrativosAs'] ?? 0) +
                                    ($ae3Array['administrativosI'] ?? 0) +
                                    ($ae3Array['administrativosIt'] ?? 0) +
                                    ($ae3Array['administrativosIa'] ?? 0) +
                                    ($ae3Array['administrativosIag'] ?? 0) +
                                    ($ae3Array['administrativosAi'] ?? 0) +
                                    ($ae3Array['administrativosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['administrativosMsc'] ?? 0) +
                                    ($ae3Array['administrativosDr'] ?? 0)
                                ),
                                'servicio' => (
                                    ($ae3Array['servicioTotalCubierta'] ?? 0) +
                                    ($ae3Array['servicioDeEllosFem'] ?? 0) +
                                    ($ae3Array['servicioJovenesTotal'] ?? 0) +
                                    ($ae3Array['servicioJovenesFem'] ?? 0) +
                                    ($ae3Array['servicioFem'] ?? 0) +
                                    ($ae3Array['servicioPt'] ?? 0) +
                                    ($ae3Array['servicioPa'] ?? 0) +
                                    ($ae3Array['servicioAs'] ?? 0) +
                                    ($ae3Array['servicioI'] ?? 0) +
                                    ($ae3Array['servicioIt'] ?? 0) +
                                    ($ae3Array['servicioIa'] ?? 0) +
                                    ($ae3Array['servicioIag'] ?? 0) +
                                    ($ae3Array['servicioAi'] ?? 0) +
                                    ($ae3Array['servicioAuxTecDoc'] ?? 0) +
                                    ($ae3Array['servicioMsc'] ?? 0) +
                                    ($ae3Array['servicioDr'] ?? 0)
                                ),
                                'operarios' => (
                                    ($ae3Array['operariosTotalCubierta'] ?? 0) +
                                    ($ae3Array['operariosDeEllosFem'] ?? 0) +
                                    ($ae3Array['operariosJovenesTotal'] ?? 0) +
                                    ($ae3Array['operariosJovenesFem'] ?? 0) +
                                    ($ae3Array['operariosFem'] ?? 0) +
                                    ($ae3Array['operariosPt'] ?? 0) +
                                    ($ae3Array['operariosPa'] ?? 0) +
                                    ($ae3Array['operariosAs'] ?? 0) +
                                    ($ae3Array['operariosI'] ?? 0) +
                                    ($ae3Array['operariosIt'] ?? 0) +
                                    ($ae3Array['operariosIa'] ?? 0) +
                                    ($ae3Array['operariosIag'] ?? 0) +
                                    ($ae3Array['operariosAi'] ?? 0) +
                                    ($ae3Array['operariosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['operariosMsc'] ?? 0) +
                                    ($ae3Array['operariosDr'] ?? 0)
                                )
                            ]
                        ];
                }
                if (in_array($categoriaEstrutura, [20])) {
                    $centrosG4[] =
                        [
                            'nombre' => $ae3Array['entidad']['siglas'] ?? null,
                            'cuadros_docentes' => [
                                'tot' => $totalCuadrosDocentes,
                                'pt' => $ae3Array['cuadrosDocentesPt'] ?? 0,
                                'pa' => $ae3Array['cuadrosDocentesPa'] ?? 0,
                                'as' => $ae3Array['cuadrosDocentesAs'] ?? 0,
                                'i' => $ae3Array['cuadrosDocentesI'] ?? 0,
                                'atd' => $ae3Array['cuadrosDocentesAuxTecDoc'] ?? 0,
                            ],
                            'cuadros_investigacion' => [
                                'tot' => $totalCuadrosInvest,
                                'it' => $ae3Array['cuadrosInvestigacionIt'] ?? 0,
                                'ia' => $ae3Array['cuadrosInvestigacionIa'] ?? 0,
                                'iag' => $ae3Array['cuadrosInvestigacionIag'] ?? 0,
                                'ai' => $ae3Array['cuadrosInvestigacionAi'] ?? 0,
                            ],
                            'profesores_completo' => [
                                'tot' => $totalProfCompleto,
                                'pt' => $ae3Array['profesoresTiempoCompletoPt'] ?? 0,
                                'pa' => $ae3Array['profesoresTiempoCompletoPa'] ?? 0,
                                'as' => $ae3Array['profesoresTiempoCompletoAs'] ?? 0,
                                'i' => $ae3Array['profesoresTiempoCompletoI'] ?? 0,
                                'atd' => $ae3Array['profesoresTiempoCompletoAuxTecDoc'] ?? 0,
                            ],
                            'asesores' => [
                                'tot' => $totalAsesores,
                                'pt' => $ae3Array['asesoresMetodologosPt'] ?? 0,
                                'pa' => $ae3Array['asesoresMetodologosPa'] ?? 0,
                                'as' => $ae3Array['asesoresMetodologosAs'] ?? 0,
                                'i' => $ae3Array['asesoresMetodologosI'] ?? 0,
                            ],
                            'investigadores' => [
                                'tot' => $totalInves,
                                'it' => $ae3Array['investigadoresIt'] ?? 0,
                                'ia' => $ae3Array['investigadoresIa'] ?? 0,
                                'iag' => $ae3Array['investigadoresIag'] ?? 0,
                                'ai' => $ae3Array['investigadoresAi'] ?? 0,
                            ],
                            'otros' => [
                                'otros_cuadros' => (
                                    ($ae3Array['otrosCuadrosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosCuadrosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosFem'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosPa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAs'] ?? 0) +
                                    ($ae3Array['otrosCuadrosI'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIt'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIa'] ?? 0) +
                                    ($ae3Array['otrosCuadrosIag'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAi'] ?? 0) +
                                    ($ae3Array['otrosCuadrosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosMsc'] ?? 0) +
                                    ($ae3Array['otrosCuadrosDr'] ?? 0)
                                ),
                                'claustro' => $claustro,
                                'tecnicos' => (
                                    ($ae3Array['otrosTecnicosTotalCubierta'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDeEllosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesTotal'] ?? 0) +
                                    ($ae3Array['otrosTecnicosJovenesFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosFem'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosPa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAs'] ?? 0) +
                                    ($ae3Array['otrosTecnicosI'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIt'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIa'] ?? 0) +
                                    ($ae3Array['otrosTecnicosIag'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAi'] ?? 0) +
                                    ($ae3Array['otrosTecnicosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosMsc'] ?? 0) +
                                    ($ae3Array['otrosTecnicosDr'] ?? 0)
                                ),
                                'administrativos' => (
                                    ($ae3Array['administrativosTotalCubierta'] ?? 0) +
                                    ($ae3Array['administrativosDeEllosFem'] ?? 0) +
                                    ($ae3Array['administrativosJovenesTotal'] ?? 0) +
                                    ($ae3Array['administrativosJovenesFem'] ?? 0) +
                                    ($ae3Array['administrativosFem'] ?? 0) +
                                    ($ae3Array['administrativosPt'] ?? 0) +
                                    ($ae3Array['administrativosPa'] ?? 0) +
                                    ($ae3Array['administrativosAs'] ?? 0) +
                                    ($ae3Array['administrativosI'] ?? 0) +
                                    ($ae3Array['administrativosIt'] ?? 0) +
                                    ($ae3Array['administrativosIa'] ?? 0) +
                                    ($ae3Array['administrativosIag'] ?? 0) +
                                    ($ae3Array['administrativosAi'] ?? 0) +
                                    ($ae3Array['administrativosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['administrativosMsc'] ?? 0) +
                                    ($ae3Array['administrativosDr'] ?? 0)
                                ),
                                'servicio' => (
                                    ($ae3Array['servicioTotalCubierta'] ?? 0) +
                                    ($ae3Array['servicioDeEllosFem'] ?? 0) +
                                    ($ae3Array['servicioJovenesTotal'] ?? 0) +
                                    ($ae3Array['servicioJovenesFem'] ?? 0) +
                                    ($ae3Array['servicioFem'] ?? 0) +
                                    ($ae3Array['servicioPt'] ?? 0) +
                                    ($ae3Array['servicioPa'] ?? 0) +
                                    ($ae3Array['servicioAs'] ?? 0) +
                                    ($ae3Array['servicioI'] ?? 0) +
                                    ($ae3Array['servicioIt'] ?? 0) +
                                    ($ae3Array['servicioIa'] ?? 0) +
                                    ($ae3Array['servicioIag'] ?? 0) +
                                    ($ae3Array['servicioAi'] ?? 0) +
                                    ($ae3Array['servicioAuxTecDoc'] ?? 0) +
                                    ($ae3Array['servicioMsc'] ?? 0) +
                                    ($ae3Array['servicioDr'] ?? 0)
                                ),
                                'operarios' => (
                                    ($ae3Array['operariosTotalCubierta'] ?? 0) +
                                    ($ae3Array['operariosDeEllosFem'] ?? 0) +
                                    ($ae3Array['operariosJovenesTotal'] ?? 0) +
                                    ($ae3Array['operariosJovenesFem'] ?? 0) +
                                    ($ae3Array['operariosFem'] ?? 0) +
                                    ($ae3Array['operariosPt'] ?? 0) +
                                    ($ae3Array['operariosPa'] ?? 0) +
                                    ($ae3Array['operariosAs'] ?? 0) +
                                    ($ae3Array['operariosI'] ?? 0) +
                                    ($ae3Array['operariosIt'] ?? 0) +
                                    ($ae3Array['operariosIa'] ?? 0) +
                                    ($ae3Array['operariosIag'] ?? 0) +
                                    ($ae3Array['operariosAi'] ?? 0) +
                                    ($ae3Array['operariosAuxTecDoc'] ?? 0) +
                                    ($ae3Array['operariosMsc'] ?? 0) +
                                    ($ae3Array['operariosDr'] ?? 0)
                                )
                            ]
                        ];
                }

            }


            $datos[] = [
                'nombre' => ' ',
                'centros' => $centrosG1
            ];
            $datos[] = [
                'nombre' => ' ',
                'centros' => $centrosG2
            ];
            $datos[] = [
                'nombre' => ' ',
                'centros' => $centrosG3
            ];
            $datos[] = [
                'nombre' => ' ',
                'centros' => $centrosG4
            ];
        }

//        $datos = [
//            // Grupo Universidades
//            [
//                'nombre' => 'UNIVERSIDADES',
//                'centros' => [
//                    [
//                        'nombre' => 'UPR',
//                        'cuadros_docentes' => [
//                            'tot' => 5,
//                            'pt' => 2,
//                            'pa' => 1,
//                            'as' => 1,
//                            'i' => 1,
//                            'atd' => 0
//                        ],
//                        'cuadros_investigacion' => [
//                            'tot' => 3,
//                            'it' => 1,
//                            'ia' => 1,
//                            'iag' => 1,
//                            'ai' => 0
//                        ],
//                        'profesores_completo' => [
//                            'tot' => 10,
//                            'pt' => 4,
//                            'pa' => 3,
//                            'as' => 2,
//                            'i' => 1,
//                            'atd' => 0
//                        ],
//                        'asesores' => [
//                            'tot' => 2,
//                            'pt' => 1,
//                            'pa' => 1,
//                            'as' => 0,
//                            'i' => 0
//                        ],
//                        'investigadores' => [
//                            'tot' => 4,
//                            'it' => 2,
//                            'ia' => 1,
//                            'iag' => 1,
//                            'ai' => 0
//                        ],
//                        'otros' => [
//                            'otros_cuadros' => 1,
//                            'claustro' => 2,
//                            'tecnicos' => 5,
//                            'administrativos' => 3,
//                            'servicio' => 2,
//                            'operarios' => 1
//                        ]
//                    ],
//                    [
//                        'nombre' => 'CUJAE',
//                        'cuadros_docentes' => ['tot' => 8, 'pt' => 3, 'pa' => 2, 'as' => 2, 'i' => 1, 'atd' => 0],
//                        'cuadros_investigacion' => ['tot' => 5, 'it' => 2, 'ia' => 1, 'iag' => 1, 'ai' => 1],
//                        'profesores_completo' => ['tot' => 15, 'pt' => 6, 'pa' => 5, 'as' => 3, 'i' => 1, 'atd' => 0],
//                        'asesores' => ['tot' => 3, 'pt' => 1, 'pa' => 1, 'as' => 1, 'i' => 0],
//                        'investigadores' => ['tot' => 7, 'it' => 3, 'ia' => 2, 'iag' => 1, 'ai' => 1],
//                        'otros' => ['otros_cuadros' => 2, 'claustro' => 3, 'tecnicos' => 8, 'administrativos' => 5, 'servicio' => 4, 'operarios' => 2]
//                    ]
//                ]
//            ],
//
//            // Grupo Centros de Investigación
//            [
//                'nombre' => 'CENTROS DE INVESTIGACIÓN',
//                'centros' => [
//                    [
//                        'nombre' => 'CENSA',
//                        'cuadros_docentes' => ['tot' => 3, 'pt' => 1, 'pa' => 1, 'as' => 1, 'i' => 0, 'atd' => 0],
//                        'cuadros_investigacion' => ['tot' => 4, 'it' => 2, 'ia' => 1, 'iag' => 1, 'ai' => 0],
//                        'profesores_completo' => ['tot' => 8, 'pt' => 3, 'pa' => 3, 'as' => 2, 'i' => 0, 'atd' => 0],
//                        'asesores' => ['tot' => 1, 'pt' => 0, 'pa' => 1, 'as' => 0, 'i' => 0],
//                        'investigadores' => ['tot' => 5, 'it' => 2, 'ia' => 2, 'iag' => 1, 'ai' => 0],
//                        'otros' => ['otros_cuadros' => 1, 'claustro' => 1, 'tecnicos' => 4, 'administrativos' => 2, 'servicio' => 1, 'operarios' => 1]
//                    ]
//                ]
//            ]
//        ];

        // Calcular subtotales para cada grupo
        foreach ($datos as &$grupo) {
            $grupo['subtotal'] = $this->calcularSubtotal($grupo['centros']);
        }
        unset($grupo); // Romper la referencia

        // Calcular total general
        $totalGeneral = $this->calcularTotalGeneral($datos);

        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es'); // Configurar el locale en español
        return $this->render('modules/rrhh/reporte/tabla1.4/index.html.twig', [
            'grupos' => $datos,
            'totalGeneral' => $totalGeneral,
            'fecha' => new \DateTime(),
            'text_fil' => 'Año: ' . $request->getSession()->get('fil_anno_tabla_1_4') . " | Mes: " .
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
                ][(int)$request->getSession()->get('fil_mes_tabla_1_4')] ?? 'Mes inválido',
            'fil_mes' => $request->getSession()->get('fil_mes_tabla_1_4'),
            'fil_anno' => $request->getSession()->get('fil_anno_tabla_1_4'),
        ]);
    }

    private function calcularSubtotal(array $centros): array
    {
        $subtotal = [
            'cuadros_docentes' => ['tot' => 0, 'pt' => 0, 'pa' => 0, 'as' => 0, 'i' => 0, 'atd' => 0],
            'cuadros_investigacion' => ['tot' => 0, 'it' => 0, 'ia' => 0, 'iag' => 0, 'ai' => 0],
            'profesores_completo' => ['tot' => 0, 'pt' => 0, 'pa' => 0, 'as' => 0, 'i' => 0, 'atd' => 0],
            'asesores' => ['tot' => 0, 'pt' => 0, 'pa' => 0, 'as' => 0, 'i' => 0],
            'investigadores' => ['tot' => 0, 'it' => 0, 'ia' => 0, 'iag' => 0, 'ai' => 0],
            'otros' => ['otros_cuadros' => 0, 'claustro' => 0, 'tecnicos' => 0, 'administrativos' => 0, 'servicio' => 0, 'operarios' => 0]
        ];

        foreach ($centros as $centro) {
            foreach ($subtotal as $categoria => &$valores) {
                foreach ($valores as $subcategoria => &$valor) {
                    if (isset($centro[$categoria][$subcategoria])) {
                        $valor += $centro[$categoria][$subcategoria];
                    }
                }
                unset($valor); // Romper la referencia
            }
            unset($valores); // Romper la referencia
        }

        return $subtotal;
    }

    private function calcularTotalGeneral(array $grupos): array
    {
        $totalGeneral = [
            'cuadros_docentes' => ['tot' => 0, 'pt' => 0, 'pa' => 0, 'as' => 0, 'i' => 0, 'atd' => 0],
            'cuadros_investigacion' => ['tot' => 0, 'it' => 0, 'ia' => 0, 'iag' => 0, 'ai' => 0],
            'profesores_completo' => ['tot' => 0, 'pt' => 0, 'pa' => 0, 'as' => 0, 'i' => 0, 'atd' => 0],
            'asesores' => ['tot' => 0, 'pt' => 0, 'pa' => 0, 'as' => 0, 'i' => 0],
            'investigadores' => ['tot' => 0, 'it' => 0, 'ia' => 0, 'iag' => 0, 'ai' => 0],
            'otros' => ['otros_cuadros' => 0, 'claustro' => 0, 'tecnicos' => 0, 'administrativos' => 0, 'servicio' => 0, 'operarios' => 0]
        ];

        foreach ($grupos as $grupo) {
            if (!isset($grupo['subtotal'])) {
                continue;
            }

            foreach ($totalGeneral as $categoria => &$valores) {
                foreach ($valores as $subcategoria => &$valor) {
                    if (isset($grupo['subtotal'][$categoria][$subcategoria])) {
                        $valor += $grupo['subtotal'][$categoria][$subcategoria];
                    }
                }
                unset($valor); // Romper la referencia
            }
            unset($valores); // Romper la referencia
        }

        return $totalGeneral;
    }

}