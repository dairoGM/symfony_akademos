<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_responsabilidad")
 */
class Responsabilidad  extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="CategoriaResponsabilidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private CategoriaResponsabilidad $categoriaResponsabilidad;

   
    public function getCategoriaResponsabilidad()
    {
        return $this->categoriaResponsabilidad;
    }

    public function setCategoriaResponsabilidad($categoriaResponsabilidad)
    {
        $this->categoriaResponsabilidad = $categoriaResponsabilidad;

        return $this;
    }
}
