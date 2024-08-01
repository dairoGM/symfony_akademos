<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Informatizacion\TipoConectividad;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_enlace_conectividad")
 */
class EnlaceConectividad extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $ed;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $anchoBanda;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $precio;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $unidadMedida;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $tipoConexion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoConectividad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoConectividad $tipoConectividad;

    /**
     * @return string|null
     */
    public function getEd(): ?string
    {
        return $this->ed;
    }

    /**
     * @param string|null $ed
     */
    public function setEd(?string $ed): void
    {
        $this->ed = $ed;
    }

    /**
     * @return string|null
     */
    public function getAnchoBanda(): ?string
    {
        return $this->anchoBanda;
    }

    /**
     * @param string|null $anchoBanda
     */
    public function setAnchoBanda(?string $anchoBanda): void
    {
        $this->anchoBanda = $anchoBanda;
    }

    /**
     * @return string|null
     */
    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    /**
     * @param string|null $precio
     */
    public function setPrecio(?string $precio): void
    {
        $this->precio = $precio;
    }

    /**
     * @return TipoConectividad|null
     */
    public function getTipoConectividad(): ?TipoConectividad
    {
        return $this->tipoConectividad;
    }

    /**
     * @param TipoConectividad|null $tipoConectividad
     */
    public function setTipoConectividad(?TipoConectividad $tipoConectividad): void
    {
        $this->tipoConectividad = $tipoConectividad;
    }

    /**
     * @return string|null
     */
    public function getUnidadMedida(): ?string
    {
        return $this->unidadMedida;
    }

    /**
     * @param string|null $unidadMedida
     */
    public function setUnidadMedida(?string $unidadMedida): void
    {
        $this->unidadMedida = $unidadMedida;
    }

    /**
     * @return string|null
     */
    public function getTipoConexion(): ?string
    {
        return $this->tipoConexion;
    }

    /**
     * @param string|null $tipoConexion
     */
    public function setTipoConexion(?string $tipoConexion): void
    {
        $this->tipoConexion = $tipoConexion;
    }


}
