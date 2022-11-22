<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_miembros_copep")
 */
class MiembrosCopep extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Copep")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Copep $copep;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Persona $miembro;


    /**
     * @ORM\ManyToOne(targetEntity="RolComision")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?RolComision $rolComision;


    public function getMiembro()
    {
        return $this->miembro;
    }

    public function setMiembro($miembro)
    {
        $this->miembro = $miembro;

        return $this;
    }

    public function getRolComision()
    {
        return $this->rolComision;
    }

    public function setRolComision($rolComision)
    {
        $this->rolComision = $rolComision;

        return $this;
    }

    public function getCopep()
    {
        return $this->copep;
    }

    public function setCopep($copep)
    {
        $this->copep = $copep;

        return $this;
    }
}
