<?php

namespace App\Entity\Security;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Estructura;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="seguridad.tbr_rol_estructura", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"rol_id", "estructura_id"})
 * })
 */
class RolEstructura extends BaseEntity
{    

    /**
     * @ORM\ManyToOne(targetEntity="Rol")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Rol $rol;

    /**
     * Entiddad padre
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Estructura")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Estructura $estructura;   


    /**
     * Get the value of rol
     */ 
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set the value of rol
     *
     * @return  self
     */ 
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }
      

    /**
     * Get entiddad padre
     */ 
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Set entiddad padre
     *
     * @return  self
     */ 
    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;

        return $this;
    }
}
