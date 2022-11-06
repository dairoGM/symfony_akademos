<?php

namespace App\Entity\Postgrado;
 
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbn_rama_ciencia")
 */
class RamaCiencia extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true, length="3")
     */
    private ?string $siglas=null;


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
