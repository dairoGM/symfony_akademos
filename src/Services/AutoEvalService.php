<?php

namespace App\Services;

use App\Entity\Planificacion\PlanIndicador;
use App\Entity\Planificacion\FormaEvaluacion;
use App\Entity\Planificacion\RangoEvaluacion;
use App\Entity\Planificacion\Evaluacion;
use App\Entity\Planificacion\Indicador;
use App\Repository\Planificacion\PlanIndicadorRepository;
use App\ExtendSys\AutoEval\AutoEval;
use Exception;

class AutoEvalService
{

    private PlanIndicadorRepository $planIndicadorRepository;

    function __construct(PlanIndicadorRepository $planIndicadorRepository)
    {
        $this->planIndicadorRepository = $planIndicadorRepository;
    }

    /**
     * Evalúa un Indicador en un Plan en base a su Forma de Evaluación
     * Y teniendo en cuenta su Valor Planificado y Valor Ejecutado en el Plan
     * Persiste en Base Datos en caso de save = true (por defecto)
     * Devuelve el PlanIndicador asociado con su Evaluación
     *
     * @param [type] $idPlan -> Id Id del Plan
     * @param [type] $idIndicador -> Id del Indicador
     * @param boolean $save -> Indica si pesiste en Base de Datos
     * @return PlanIndicador
     */
    public function evaluarPlanIndicadorById($idPlanIndicador, $save = true): ?Evaluacion
    {
        //Obteniendo el Plan Indicador
        $planIndicador = $this->planIndicadorRepository->getById($idPlanIndicador);

        return $this->evaluar($planIndicador, $save);
    }

    /**
     * Evalúa un Indicador en un Plan en base a su Forma de Evaluación
     * Y teniendo en cuenta su Valor Planificado y Valor Ejecutado en el Plan
     * Persiste en Base Datos en caso de save = true (por defecto)
     * Devuelve el PlanIndicador asociado con su Evaluación
     *
     * @param [type] $idPlan -> Id Id del Plan
     * @param [type] $idIndicador -> Id del Indicador
     * @param boolean $save -> Indica si pesiste en Base de Datos
     * @return PlanIndicador
     */
    public function evaluarPlanIndicadorByIds($idPlan, $idIndicador, $save = true): ?Evaluacion
    {
        //Obteniendo el Plan Indicador
        $planIndicador = $this->planIndicadorRepository->getPlanIndicadorByIdPlanAndIdIndicador($idIndicador, $idIndicador);

        if (!isset($planIndicador)) {
            throw new Exception("El indicador no existe en el plan");
        }

        return $this->evaluar($planIndicador, $save);
    }


    /**
     * Devuelve una Evaluacion para un indicador y el valor cuantitvo 
     *
     * @param Indicador $indicador
     * @param [type] $evalQty
     * @return Evaluacion
     */
    public function evaluacionGloblaIndicador(Indicador $indicador, $evalQty) : ?Evaluacion
    {
        //Obteniendo los Rangos asociados y Propuesta de Evaluacion
        $formaEvaluacion = $indicador->getDefaultFormaEvaluacion();

        return $this->evaluacionGlobal($formaEvaluacion, $evalQty);
    }
    
    /**
     * Devuelve una Evaluacion dado su Forma de Evaluacion y el valor cuantitvo
     *
     * @param FormaEvaluacion $formaEvaluacion
     * @param [type] $evalQty Valor (Valor del porciento, valor real..etc)
     * @return Evaluacion
     */
    public function evaluacionGlobal(FormaEvaluacion $formaEvaluacion, $evalQty) : ?Evaluacion
    {
        //Obteniendo los Rangos asociados y Propuesta de Evaluacion
        $rangosEvaluacion = $formaEvaluacion->getRangosEvaluacion();
        $evaluacion = $this->propuestaEvaluacion($rangosEvaluacion, $evalQty);

        return $evaluacion;
    }

    /**
     * Evalua un PlanIndicador (metodo inicial)
     *
     * @param PlanIndicador $planIndicador
     * @return Evaluacion
     */
    private function evaluar(PlanIndicador $planIndicador, $save = true)
    {        
        if (isset($planIndicador)) {
            $evaluacion = $this->aplicarFormaEvaluación($planIndicador);

            if (isset($evaluacion) && $save) {
                $evaluacion = $this->persitEvaluacion($planIndicador, $evaluacion);
            }

            return $evaluacion;
        }

        return null;
    }


    /**
     * Aplica la Forma de Evaluación al PlanIndicador
     */
    private function aplicarFormaEvaluación(PlanIndicador $planIndicador)
    {
        //Obteniendo la forma de Evaluación
        $formaEvaluacion = $planIndicador->getFormaEvaluacion();

        if (!isset($formaEvaluacion)) {
            throw new Exception("El indicador no tiene forma de evaluación");
        }

        //Obtiendo evaluacion cuantitativa
        $evalQty = $this->aplicarFormulaBase($planIndicador, $formaEvaluacion);

        if (!isset($evalQty)) {
            return null;
        }
        //Obteniendo los Rangos asociados y Propuesta de Evaluacion
        $rangosEvaluacion = $formaEvaluacion->getRangosEvaluacion();
        $evaluacion = $this->propuestaEvaluacion($rangosEvaluacion, $evalQty);

        return $evaluacion;
    }


    /**
     * Aplica uan Formula para obtener un valor cualitativo para Evaluar el Indicador
     *
     * @param PlanIndicador $planIndicador
     * @param FormaEvaluacion $formaEvaluacion
     * @return floar
     */
    private function aplicarFormulaBase(PlanIndicador $planIndicador, FormaEvaluacion $formaEvaluacion): float
    {
        $plan = $planIndicador->getPlanValor();
        $real = $planIndicador->getPlanReal();

        if (!isset($real)) {
            return null;
        }

        $formula = $formaEvaluacion->getFormula();

        if (!isset($planIndicador)) {
            throw new Exception("La fórmula no existe");
        }

        return AutoEval::aplicarFormula($formula->getTokenTipoFormula(), $plan, $real);
    }

    /**
     * Propone una Evaluacion resolvinedo los Rangos asociados y el valor cuantitativo
     *
     * @param [type] $rangosEvaluacion Rangos de evaliacion
     * @param [type] $evalQty
     * @return Evaluacion|null
     */
    private function propuestaEvaluacion($rangosEvaluacion, $evalQty): ?Evaluacion
    {
        $minRange = null;
        $minEval = null;
        $maxRange = null;
        $maxEval = null;

        if (isset($evalQty) && isset($rangosEvaluacion)) {
            //Comparando por rango
            foreach ($rangosEvaluacion as $rango) {

                //Compara rango
                if ($evalQty >= $rango->getMinValue() && $evalQty < $rango->getMaxValue()) {
                    return $rango->getEvaluacion();
                }

                //Pin Min Value
                if (!isset($minRange) || $rango->getMinValue() < $minRange) {
                    $minRange = $rango->getMinValue();
                    $minEval = $rango->getEvaluacion();
                }
                //Pin Max Value
                if (!isset($maxRange) || $rango->getMaxValue() > $maxRange) {
                    $maxRange = $rango->getMaxValue();
                    $maxEval = $rango->getEvaluacion();
                }
            }

            //Eval Limits
            if (isset($minRange) && isset($minEval) && $evalQty <= $minRange) {
                return $minEval;
            }
            if (isset($maxRange) && isset($maxEval) && $evalQty >= $maxRange) {
                return $maxEval;
            }
        }

        return null;
    }


    /**
     * Persiste la Evaluacion de Plan Indicador
     *
     * @param PlanIndicador $planIndicador
     * @param Evaluacion $eval
     * @return Evaluacion
     */
    private function persitEvaluacion(PlanIndicador $planIndicador, Evaluacion $evaluacion): Evaluacion
    {
        $planIndicador->setEvaluacion($evaluacion);
        $planIndicador->setEvaluacionTxt($evaluacion->getSiglas());

        $planIndicador = $this->planIndicadorRepository->edit($planIndicador, true);

        return $planIndicador->getEvaluacion();
    }
}