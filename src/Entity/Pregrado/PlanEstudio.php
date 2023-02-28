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
     * @ORM\ManyToOne(targetEntity="\App\Entity\Personal\Carrera")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Personal\Carrera $carrera;

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
     * @ORM\JoinColumn(nullable=false)
     */
    private ?\App\Entity\Postgrado\RamaCiencia $ramaCiencia;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $planEstudio = null;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $duracionCursoDiurno;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $duracionCursoPorEncuentro;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $duracionCursoDistancia;


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
     * @return Carrera|null
     */
    public function getCarrera(): ?Carrera
    {
        return $this->carrera;
    }

    /**
     * @param Carrera|null $carrera
     */
    public function setCarrera(?Carrera $carrera): void
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
     * @return int|null
     */
    public function getDuracionCursoDistancia(): ?int
    {
        return $this->duracionCursoDistancia;
    }

    /**
     * @param int|null $duracionCursoDistancia
     */
    public function setDuracionCursoDistancia(?int $duracionCursoDistancia): void
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





}
