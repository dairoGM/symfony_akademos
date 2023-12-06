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
 * @ORM\Table(name="pregrado.tbr_modificacion_plan_estudio")
 */
class ModificacionPlanEstudio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\PlanEstudio")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?PlanEstudio $planEstudio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\CursoAcademico")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?CursoAcademico $cursoAcademico;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAprobacion;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $planEstudioDoc = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamen = null;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $duracionCursoDiurno;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $duracionCursoPorEncuentro;

    /**
     * @ORM\Column(type="string", nullable=false)
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
    private ?string $descripcion = null;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $creado;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $actualizado;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $documentoEjecutivo = null;

    /**
     * @return PlanEstudio|null
     */
    public function getPlanEstudio(): ?PlanEstudio
    {
        return $this->planEstudio;
    }

    /**
     * @param PlanEstudio|null $planEstudio
     */
    public function setPlanEstudio(?PlanEstudio $planEstudio): void
    {
        $this->planEstudio = $planEstudio;
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
     * @return string|null
     */
    public function getPlanEstudioDoc(): ?string
    {
        return $this->planEstudioDoc;
    }

    /**
     * @param string|null $planEstudioDoc
     */
    public function setPlanEstudioDoc(?string $planEstudioDoc): void
    {
        $this->planEstudioDoc = $planEstudioDoc;
    }

    /**
     * @return string|null
     */
    public function getDictamen(): ?string
    {
        return $this->dictamen;
    }

    /**
     * @param string|null $dictamen
     */
    public function setDictamen(?string $dictamen): void
    {
        $this->dictamen = $dictamen;
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


    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCreado()
    {
        return $this->creado;
    }

    public function getActualizado()
    {
        return $this->actualizado;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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
