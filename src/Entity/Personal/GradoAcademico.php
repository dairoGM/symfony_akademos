<?php

namespace App\Entity\Personal;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="siglas", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @ORM\Table(name="personal.tbn_grado_academico")
 */
class GradoAcademico extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="3")
     */
    private ?string $siglas;   

   
    public function getSiglas()
    {
        return $this->siglas;
    }
    
    public function setSiglas($siglas)
    {
        $this->siglas = $siglas;

        return $this;
    }
}
