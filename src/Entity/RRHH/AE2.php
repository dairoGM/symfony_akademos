<?php

namespace App\Entity\RRHH;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Estructura\Estructura;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="rrhh.tbd_ae2", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_mes_anio", columns={"mes", "anno"})})
 * @UniqueEntity(fields={"mes", "anno"}, message="Ya existe un registro para este mes y año.")
 */
class AE2 extends BaseEntity
{

    /** @ORM\Column(type="integer", nullable=true) */
    private $mes;

    /** @ORM\Column(type="integer", nullable=true) */
    private $anno;


    /** @ORM\Column(type="float", nullable=true) */
    private $totalPlantillaAprobada;

    /** @ORM\Column(type="float", nullable=true) */
    private $totalPlantillaCubierta;

    /** @ORM\Column(type="float", nullable=true) */
    private $totalGeneralContratos;

    /** @ORM\Column(type="float", nullable=true) */
    private $totalContratosProfesoresTiempoDeterminado;

    /** @ORM\Column(type="float", nullable=true) */
    private $profesoresTiempoCompleto;

    /** @ORM\Column(type="float", nullable=true) */
    private $totalContratosNoDocentes;

    /** @ORM\Column(type="float", nullable=true) */
    private $contratosNoDocentesConRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $contratosPorSustitucion;

    /** @ORM\Column(type="float", nullable=true) */
    private $periodoPrueba;

    /** @ORM\Column(type="float", nullable=true) */
    private $serenosAuxiliaresLimpieza;

    /** @ORM\Column(type="float", nullable=true) */
    private $laboresAgricolas;

    /** @ORM\Column(type="float", nullable=true) */
    private $jubilados;

    /** @ORM\Column(type="float", nullable=true) */
    private $otrosConRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $contratosNoDocentesSinRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $serenosAuxiliaresLimpiezaSinRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $laboresAgricolasSinRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $jubiladosSinRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $ejecucionObra;

    /** @ORM\Column(type="float", nullable=true) */
    private $otrosSinRespaldo;

    /** @ORM\Column(type="float", nullable=true) */
    private $reservaCientificaPreparacion;

    /** @ORM\Column(type="float", nullable=true) */
    private $recienGraduadosPreparacion;

    /** @ORM\Column(type="float", nullable=true) */
    private $reservaDireccionProvincialTrabajo;

    /** @ORM\Column(type="float", nullable=true) */
    private $tecnicosMediosPreparacion;

    /** @ORM\Column(type="float", nullable=true) */
    private $totalEstudiantesUniversidadContratados;

    /** @ORM\Column(type="float", nullable=true) */
    private $estudiantesAuxiliaresTecnicosDocencia;

    /** @ORM\Column(type="float", nullable=true) */
    private $estudiantesCargosNoDocentes;

    /**
     * @ORM\ManyToOne(targetEntity=Estructura::class)
     */
    private $entidad = null;

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTotalPlantillaAprobada()
    {
        return $this->totalPlantillaAprobada;
    }

    /**
     * @param mixed $totalPlantillaAprobada
     */
    public function setTotalPlantillaAprobada($totalPlantillaAprobada): void
    {
        $this->totalPlantillaAprobada = $totalPlantillaAprobada;
    }

    /**
     * @return mixed
     */
    public function getTotalPlantillaCubierta()
    {
        return $this->totalPlantillaCubierta;
    }

    /**
     * @param mixed $totalPlantillaCubierta
     */
    public function setTotalPlantillaCubierta($totalPlantillaCubierta): void
    {
        $this->totalPlantillaCubierta = $totalPlantillaCubierta;
    }

    /**
     * @return mixed
     */
    public function getTotalGeneralContratos()
    {
        return $this->totalGeneralContratos;
    }

    /**
     * @param mixed $totalGeneralContratos
     */
    public function setTotalGeneralContratos($totalGeneralContratos): void
    {
        $this->totalGeneralContratos = $totalGeneralContratos;
    }

    /**
     * @return mixed
     */
    public function getTotalContratosProfesoresTiempoDeterminado()
    {
        return $this->totalContratosProfesoresTiempoDeterminado;
    }

    /**
     * @param mixed $totalContratosProfesoresTiempoDeterminado
     */
    public function setTotalContratosProfesoresTiempoDeterminado($totalContratosProfesoresTiempoDeterminado): void
    {
        $this->totalContratosProfesoresTiempoDeterminado = $totalContratosProfesoresTiempoDeterminado;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompleto()
    {
        return $this->profesoresTiempoCompleto;
    }

    /**
     * @param mixed $profesoresTiempoCompleto
     */
    public function setProfesoresTiempoCompleto($profesoresTiempoCompleto): void
    {
        $this->profesoresTiempoCompleto = $profesoresTiempoCompleto;
    }

    /**
     * @return mixed
     */
    public function getTotalContratosNoDocentes()
    {
        return $this->totalContratosNoDocentes;
    }

    /**
     * @param mixed $totalContratosNoDocentes
     */
    public function setTotalContratosNoDocentes($totalContratosNoDocentes): void
    {
        $this->totalContratosNoDocentes = $totalContratosNoDocentes;
    }

    /**
     * @return mixed
     */
    public function getContratosNoDocentesConRespaldo()
    {
        return $this->contratosNoDocentesConRespaldo;
    }

    /**
     * @param mixed $contratosNoDocentesConRespaldo
     */
    public function setContratosNoDocentesConRespaldo($contratosNoDocentesConRespaldo): void
    {
        $this->contratosNoDocentesConRespaldo = $contratosNoDocentesConRespaldo;
    }

    /**
     * @return mixed
     */
    public function getContratosPorSustitucion()
    {
        return $this->contratosPorSustitucion;
    }

    /**
     * @param mixed $contratosPorSustitucion
     */
    public function setContratosPorSustitucion($contratosPorSustitucion): void
    {
        $this->contratosPorSustitucion = $contratosPorSustitucion;
    }

    /**
     * @return mixed
     */
    public function getPeriodoPrueba()
    {
        return $this->periodoPrueba;
    }

    /**
     * @param mixed $periodoPrueba
     */
    public function setPeriodoPrueba($periodoPrueba): void
    {
        $this->periodoPrueba = $periodoPrueba;
    }

    /**
     * @return mixed
     */
    public function getSerenosAuxiliaresLimpieza()
    {
        return $this->serenosAuxiliaresLimpieza;
    }

    /**
     * @param mixed $serenosAuxiliaresLimpieza
     */
    public function setSerenosAuxiliaresLimpieza($serenosAuxiliaresLimpieza): void
    {
        $this->serenosAuxiliaresLimpieza = $serenosAuxiliaresLimpieza;
    }

    /**
     * @return mixed
     */
    public function getLaboresAgricolas()
    {
        return $this->laboresAgricolas;
    }

    /**
     * @param mixed $laboresAgricolas
     */
    public function setLaboresAgricolas($laboresAgricolas): void
    {
        $this->laboresAgricolas = $laboresAgricolas;
    }

    /**
     * @return mixed
     */
    public function getJubilados()
    {
        return $this->jubilados;
    }

    /**
     * @param mixed $jubilados
     */
    public function setJubilados($jubilados): void
    {
        $this->jubilados = $jubilados;
    }

    /**
     * @return mixed
     */
    public function getOtrosConRespaldo()
    {
        return $this->otrosConRespaldo;
    }

    /**
     * @param mixed $otrosConRespaldo
     */
    public function setOtrosConRespaldo($otrosConRespaldo): void
    {
        $this->otrosConRespaldo = $otrosConRespaldo;
    }

    /**
     * @return mixed
     */
    public function getContratosNoDocentesSinRespaldo()
    {
        return $this->contratosNoDocentesSinRespaldo;
    }

    /**
     * @param mixed $contratosNoDocentesSinRespaldo
     */
    public function setContratosNoDocentesSinRespaldo($contratosNoDocentesSinRespaldo): void
    {
        $this->contratosNoDocentesSinRespaldo = $contratosNoDocentesSinRespaldo;
    }

    /**
     * @return mixed
     */
    public function getSerenosAuxiliaresLimpiezaSinRespaldo()
    {
        return $this->serenosAuxiliaresLimpiezaSinRespaldo;
    }

    /**
     * @param mixed $serenosAuxiliaresLimpiezaSinRespaldo
     */
    public function setSerenosAuxiliaresLimpiezaSinRespaldo($serenosAuxiliaresLimpiezaSinRespaldo): void
    {
        $this->serenosAuxiliaresLimpiezaSinRespaldo = $serenosAuxiliaresLimpiezaSinRespaldo;
    }

    /**
     * @return mixed
     */
    public function getLaboresAgricolasSinRespaldo()
    {
        return $this->laboresAgricolasSinRespaldo;
    }

    /**
     * @param mixed $laboresAgricolasSinRespaldo
     */
    public function setLaboresAgricolasSinRespaldo($laboresAgricolasSinRespaldo): void
    {
        $this->laboresAgricolasSinRespaldo = $laboresAgricolasSinRespaldo;
    }

    /**
     * @return mixed
     */
    public function getJubiladosSinRespaldo()
    {
        return $this->jubiladosSinRespaldo;
    }

    /**
     * @param mixed $jubiladosSinRespaldo
     */
    public function setJubiladosSinRespaldo($jubiladosSinRespaldo): void
    {
        $this->jubiladosSinRespaldo = $jubiladosSinRespaldo;
    }

    /**
     * @return mixed
     */
    public function getEjecucionObra()
    {
        return $this->ejecucionObra;
    }

    /**
     * @param mixed $ejecucionObra
     */
    public function setEjecucionObra($ejecucionObra): void
    {
        $this->ejecucionObra = $ejecucionObra;
    }

    /**
     * @return mixed
     */
    public function getOtrosSinRespaldo()
    {
        return $this->otrosSinRespaldo;
    }

    /**
     * @param mixed $otrosSinRespaldo
     */
    public function setOtrosSinRespaldo($otrosSinRespaldo): void
    {
        $this->otrosSinRespaldo = $otrosSinRespaldo;
    }

    /**
     * @return mixed
     */
    public function getReservaCientificaPreparacion()
    {
        return $this->reservaCientificaPreparacion;
    }

    /**
     * @param mixed $reservaCientificaPreparacion
     */
    public function setReservaCientificaPreparacion($reservaCientificaPreparacion): void
    {
        $this->reservaCientificaPreparacion = $reservaCientificaPreparacion;
    }

    /**
     * @return mixed
     */
    public function getRecienGraduadosPreparacion()
    {
        return $this->recienGraduadosPreparacion;
    }

    /**
     * @param mixed $recienGraduadosPreparacion
     */
    public function setRecienGraduadosPreparacion($recienGraduadosPreparacion): void
    {
        $this->recienGraduadosPreparacion = $recienGraduadosPreparacion;
    }

    /**
     * @return mixed
     */
    public function getReservaDireccionProvincialTrabajo()
    {
        return $this->reservaDireccionProvincialTrabajo;
    }

    /**
     * @param mixed $reservaDireccionProvincialTrabajo
     */
    public function setReservaDireccionProvincialTrabajo($reservaDireccionProvincialTrabajo): void
    {
        $this->reservaDireccionProvincialTrabajo = $reservaDireccionProvincialTrabajo;
    }

    /**
     * @return mixed
     */
    public function getTecnicosMediosPreparacion()
    {
        return $this->tecnicosMediosPreparacion;
    }

    /**
     * @param mixed $tecnicosMediosPreparacion
     */
    public function setTecnicosMediosPreparacion($tecnicosMediosPreparacion): void
    {
        $this->tecnicosMediosPreparacion = $tecnicosMediosPreparacion;
    }

    /**
     * @return mixed
     */
    public function getTotalEstudiantesUniversidadContratados()
    {
        return $this->totalEstudiantesUniversidadContratados;
    }

    /**
     * @param mixed $totalEstudiantesUniversidadContratados
     */
    public function setTotalEstudiantesUniversidadContratados($totalEstudiantesUniversidadContratados): void
    {
        $this->totalEstudiantesUniversidadContratados = $totalEstudiantesUniversidadContratados;
    }

    /**
     * @return mixed
     */
    public function getEstudiantesAuxiliaresTecnicosDocencia()
    {
        return $this->estudiantesAuxiliaresTecnicosDocencia;
    }

    /**
     * @param mixed $estudiantesAuxiliaresTecnicosDocencia
     */
    public function setEstudiantesAuxiliaresTecnicosDocencia($estudiantesAuxiliaresTecnicosDocencia): void
    {
        $this->estudiantesAuxiliaresTecnicosDocencia = $estudiantesAuxiliaresTecnicosDocencia;
    }

    /**
     * @return mixed
     */
    public function getEstudiantesCargosNoDocentes()
    {
        return $this->estudiantesCargosNoDocentes;
    }

    /**
     * @param mixed $estudiantesCargosNoDocentes
     */
    public function setEstudiantesCargosNoDocentes($estudiantesCargosNoDocentes): void
    {
        $this->estudiantesCargosNoDocentes = $estudiantesCargosNoDocentes;
    }

    /**
     * @return null
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * @param null $entidad
     */
    public function setEntidad($entidad): void
    {
        $this->entidad = $entidad;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getAnno()
    {
        return $this->anno;
    }

    /**
     * @param mixed $anno
     */
    public function setAnno($anno): void
    {
        $this->anno = $anno;
    }


}
