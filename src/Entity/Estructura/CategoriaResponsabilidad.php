<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_categoria_responsabilidad")
 */
class CategoriaResponsabilidad extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private string $color;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaResponsabilidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private CategoriaResponsabilidad $categoriaResponsabilidad;

    public function getColor()
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

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
