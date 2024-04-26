<?php

namespace App\Entity\Tramite;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Pais;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbr_plan_mision_detalles")
 */
class PlanMisionDetalles extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\PlanMision")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?PlanMision $planMision;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $persona;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Pais")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Pais $pais;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $objetivo = null;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $duracion;

    /**
     * @return Persona|null
     */
    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    /**
     * @param Persona|null $persona
     */
    public function setPersona(?Persona $persona): void
    {
        $this->persona = $persona;
    }

    /**
     * @return Pais|null
     */
    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    /**
     * @param Pais|null $pais
     */
    public function setPais(?Pais $pais): void
    {
        $this->pais = $pais;
    }

    /**
     * @return string|null
     */
    public function getObjetivo(): ?string
    {
        return $this->objetivo;
    }

    /**
     * @param string|null $objetivo
     */
    public function setObjetivo(?string $objetivo): void
    {
        $this->objetivo = $objetivo;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return int|null
     */
    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    /**
     * @param int|null $duracion
     */
    public function setDuracion(?int $duracion): void
    {
        $this->duracion = $duracion;
    }

    /**
     * @return PlanMision|null
     */
    public function getPlanMision(): ?PlanMision
    {
        return $this->planMision;
    }

    /**
     * @param PlanMision|null $planMision
     */
    public function setPlanMision(?PlanMision $planMision): void
    {
        $this->planMision = $planMision;
    }


}
