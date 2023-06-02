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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }
}
