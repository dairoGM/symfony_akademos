<?php

namespace App\Entity\Personal;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Responsabilidad;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal.tbd_responsable")
 */
class Responsable extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Persona $persona;


    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * Get the value of persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set the value of persona
     *
     * @return  self
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get the value of activo
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set the value of activo
     *
     * @return  self
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }
}