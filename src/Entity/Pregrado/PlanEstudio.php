<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Personal\Carrera;
use App\Entity\Postgrado\RamaCiencia;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbd_plan_estudio")
 */
class PlanEstudio extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="CursoAcademico")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?CursoAcademico $cursoAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Pregrado\SolicitudProgramaAcademico $carrera;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $annoAprobacion;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAprobacion;


    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Postgrado\RamaCiencia")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Postgrado\RamaCiencia $ramaCiencia;

    /**
     * @ORM\ManyToOne(targetEntity="OrganismoDemandante")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?OrganismoDemandante $organismoDemandante;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\OrganismoFormador")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?OrganismoFormador $organismoFormador;

    /**
     * @ORM\ManyToOne(targetEntity="TipoProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoProgramaAcademico $tipoProgramaAcademico;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $planEstudio = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $duracionCursoDiurno;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $duracionCursoPorEncuentro;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $duracionCursoDistancia;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $descripcionPlanEstudio = null;

    /**
     * @ORM\ManyToOne(targetEntity="Oace")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Oace $oace;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $documentoEjecutivo = null;

    /**
     * @return CursoAcademico|null
     */
    public function getCursoAcademico(): ?CursoAcademico
    {
        return $this->cursoAcademico;
    }

    /**
     * @param CursoAcademico|null $cursoAcademico
     */
    public function setCursoAcademico(?CursoAcademico $cursoAcademico): void
    {
        $this->cursoAcademico = $cursoAcademico;
    }

    /**
     * @return SolicitudProgramaAcademico|null
     */
    public function getCarrera(): ?SolicitudProgramaAcademico
    {
        return $this->carrera;
    }

    /**
     * @param SolicitudProgramaAcademico|null $carrera
     */
    public function setCarrera(?SolicitudProgramaAcademico $carrera): void
    {
        $this->carrera = $carrera;
    }



    /**
     * @return int|null
     */
    public function getAnnoAprobacion(): ?int
    {
        return $this->annoAprobacion;
    }

    /**
     * @param int|null $annoAprobacion
     */
    public function setAnnoAprobacion(?int $annoAprobacion): void
    {
        $this->annoAprobacion = $annoAprobacion;
    }

    /**
     * @return mixed
     */
    public function getFechaAprobacion()
    {
        return $this->fechaAprobacion;
    }

    /**
     * @param mixed $fechaAprobacion
     */
    public function setFechaAprobacion($fechaAprobacion): void
    {
        $this->fechaAprobacion = $fechaAprobacion;
    }

    /**
     * @return RamaCiencia|null
     */
    public function getRamaCiencia(): ?RamaCiencia
    {
        return $this->ramaCiencia;
    }

    /**
     * @param RamaCiencia|null $ramaCiencia
     */
    public function setRamaCiencia(?RamaCiencia $ramaCiencia): void
    {
        $this->ramaCiencia = $ramaCiencia;
    }

    /**
     * @return string|null
     */
    public function getPlanEstudio(): ?string
    {
        return $this->planEstudio;
    }

    /**
     * @param string|null $planEstudio
     */
    public function setPlanEstudio(?string $planEstudio): void
    {
        $this->planEstudio = $planEstudio;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoDiurno(): ?int
    {
        return $this->duracionCursoDiurno;
    }

    /**
     * @param int|null $duracionCursoDiurno
     */
    public function setDuracionCursoDiurno(?int $duracionCursoDiurno): void
    {
        $this->duracionCursoDiurno = $duracionCursoDiurno;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoPorEncuentro(): ?int
    {
        return $this->duracionCursoPorEncuentro;
    }

    /**
     * @param int|null $duracionCursoPorEncuentro
     */
    public function setDuracionCursoPorEncuentro(?int $duracionCursoPorEncuentro): void
    {
        $this->duracionCursoPorEncuentro = $duracionCursoPorEncuentro;
    }

    /**
     * @return string|null
     */
    public function getDuracionCursoDistancia(): ?string
    {
        return $this->duracionCursoDistancia;
    }

    /**
     * @param string|null $duracionCursoDistancia
     */
    public function setDuracionCursoDistancia(?string $duracionCursoDistancia): void
    {
        $this->duracionCursoDistancia = $duracionCursoDistancia;
    }




    /**
     * @return string|null
     */
    public function getDescripcionPlanEstudio(): ?string
    {
        return $this->descripcionPlanEstudio;
    }

    /**
     * @param string|null $descripcionPlanEstudio
     */
    public function setDescripcionPlanEstudio(?string $descripcionPlanEstudio): void
    {
        $this->descripcionPlanEstudio = $descripcionPlanEstudio;
    }

    /**
     * @return OrganismoDemandante|null
     */
    public function getOrganismoDemandante(): ?OrganismoDemandante
    {
        return $this->organismoDemandante;
    }

    /**
     * @param OrganismoDemandante|null $organismoDemandante
     */
    public function setOrganismoDemandante(?OrganismoDemandante $organismoDemandante): void
    {
        $this->organismoDemandante = $organismoDemandante;
    }

    /**
     * @return TipoProgramaAcademico|null
     */
    public function getTipoProgramaAcademico(): ?TipoProgramaAcademico
    {
        return $this->tipoProgramaAcademico;
    }

    /**
     * @param TipoProgramaAcademico|null $tipoProgramaAcademico
     */
    public function setTipoProgramaAcademico(?TipoProgramaAcademico $tipoProgramaAcademico): void
    {
        $this->tipoProgramaAcademico = $tipoProgramaAcademico;
    }

    /**
     * @return Oace|null
     */
    public function getOace(): ?Oace
    {
        return $this->oace;
    }

    /**
     * @param Oace|null $oace
     */
    public function setOace(?Oace $oace): void
    {
        $this->oace = $oace;
    }

    /**
     * @return OrganismoFormador|null
     */
    public function getOrganismoFormador(): ?OrganismoFormador
    {
        return $this->organismoFormador;
    }

    /**
     * @param OrganismoFormador|null $organismoFormador
     */
    public function setOrganismoFormador(?OrganismoFormador $organismoFormador): void
    {
        $this->organismoFormador = $organismoFormador;
    }

    /**
     * @return string|null
     */
    public function getDocumentoEjecutivo(): ?string
    {
        return $this->documentoEjecutivo;
    }

    /**
     * @param string|null $documentoEjecutivo
     */
    public function setDocumentoEjecutivo(?string $documentoEjecutivo): void
    {
        $this->documentoEjecutivo = $documentoEjecutivo;
    }



}
