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


}
