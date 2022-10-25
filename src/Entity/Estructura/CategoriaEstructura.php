<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_categoria_estructura")
 */
class CategoriaEstructura extends BaseNomenclator
{
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $color;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $tributaCredencial = false;
    
    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getTributaCredencial()
    {
        return $this->tributaCredencial;
    }
  
    public function setTributaCredencial($tributaCredencial)
    {
        $this->tributaCredencial = $tributaCredencial;

        return $this;
    }
}
