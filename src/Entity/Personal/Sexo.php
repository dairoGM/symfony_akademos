<?php

namespace App\Entity\Personal;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal.tbn_sexo")
 * @UniqueEntity(fields="siglas", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Sexo extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="3")
     */
    private ?string $siglas;   
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $extId;

    public function getExtId(): ?string
    {
        return $this->extId;
    }

    public function setExtId(?string $extId): self
    {
        $this->extId = $extId;

        return $this;
    }

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
