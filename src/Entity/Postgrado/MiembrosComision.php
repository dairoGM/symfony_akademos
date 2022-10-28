<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_miembros_comision")
 */
class MiembrosComision extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Comision")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Comision $comision;
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

    public function getComision()
    {
        return $this->comision;
    }

    public function setComision($comision)
    {
        $this->comision = $comision;

        return $this;
    }
}
