<?php

namespace App\Entity\Evaluacion;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use App\Entity\BaseNomenclator;
use App\Entity\Evaluacion\RolComision;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion.tbr_miembros_comision")
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

    /**
     * @return Comision|null
     */
    public function getComision(): ?Comision
    {
        return $this->comision;
    }

    /**
     * @param Comision|null $comision
     */
    public function setComision(?Comision $comision): void
    {
        $this->comision = $comision;
    }

    /**
     * @return Persona|null
     */
    public function getMiembro(): ?Persona
    {
        return $this->miembro;
    }

    /**
     * @param Persona|null $miembro
     */
    public function setMiembro(?Persona $miembro): void
    {
        $this->miembro = $miembro;
    }

    /**
     * @return \App\Entity\Evaluacion\RolComision|null
     */
    public function getRolComision(): ?\App\Entity\Evaluacion\RolComision
    {
        return $this->rolComision;
    }

    /**
     * @param \App\Entity\Evaluacion\RolComision|null $rolComision
     */
    public function setRolComision(?\App\Entity\Evaluacion\RolComision $rolComision): void
    {
        $this->rolComision = $rolComision;
    }


}
