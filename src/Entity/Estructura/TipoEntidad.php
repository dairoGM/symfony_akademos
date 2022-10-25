<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_tipo_entidad")
 */
class TipoEntidad extends BaseNomenclator
{

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $color;

    /**
     * @ORM\ManyToOne(targetEntity="TipoEntidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoEntidad $tipoEntidad;

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getTipoEntidad()
    {
        return $this->tipoEntidad;
    }
  
    public function setTipoEntidad($tipoEntidad)
    {
        $this->tipoEntidad = $tipoEntidad;

        return $this;
    }
}
