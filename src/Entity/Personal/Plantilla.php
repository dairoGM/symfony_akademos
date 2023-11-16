<?php

namespace App\Entity\Personal;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Responsabilidad;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal.tbd_plantilla")
 */
class Plantilla extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=36, nullable=false)
     */
    private ?bool $idExpediente = true; 

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Persona $persona;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Estructura")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Estructura $estructura;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Responsabilidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Responsabilidad $responsabilidad;
    
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * Get the value of responsabilidad
     */ 
    public function getResponsabilidad()
    {
        return $this->responsabilidad;
    }

    /**
     * Set the value of responsabilidad
     *
     * @return  self
     */ 
    public function setResponsabilidad($responsabilidad)
    {
        $this->responsabilidad = $responsabilidad;

        return $this;
    }

    /**
     * Get the value of estructura
     */ 
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Set the value of estructura
     *
     * @return  self
     */ 
    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;

        return $this;
    }

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