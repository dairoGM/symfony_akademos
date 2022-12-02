<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use App\Entity\Postgrado\RolComision;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_miembros_comision_nacional")
 */
class MiembrosComisionNacional extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="ComisionNacional")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?ComisionNacional $comision;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Persona $miembro;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\RolComision")
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

    /**
     * @return RolComision|null
     */
    public function getRolComision(): ?RolComision
    {
        return $this->rolComision;
    }

    /**
     * @param RolComision|null $rolComision
     */
    public function setRolComision(?RolComision $rolComision): void
    {
        $this->rolComision = $rolComision;
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
