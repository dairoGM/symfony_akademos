<?php

namespace App\Entity\Postgrado;
 
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbn_categoria_categorizacion")
 */
class CategoriaCategorizacion extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="3")
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
