<?php

namespace App\Entity\Estructura;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbr_plaza",
 * uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uniq_estructura_plaza_estructura_responsabilidad", columns={"estructura_id", "responsabilidad_id"})
 * })
 */
class Plaza extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Estructura")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Estructura $estructura;

    /**
     * @ORM\ManyToOne(targetEntity="Responsabilidad")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Responsabilidad $responsabilidad;

    /** 
     * @ORM\Column(type="integer", nullable=false)
     */
    protected ?int $cantidad = 0;    
    
    public function getEstructura()
    {
        return $this->estructura;
    }

    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;

        return $this;
    }

    public function getResponsabilidad()
    {
        return $this->responsabilidad;
    }
   
    public function setResponsabilidad($responsabilidad)
    {
        $this->responsabilidad = $responsabilidad;

        return $this;
    }
    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}
