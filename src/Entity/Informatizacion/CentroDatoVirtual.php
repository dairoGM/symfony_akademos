<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseNomenclator;
use App\Entity\Estructura\Estructura;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Informatizacion\TipoConectividad;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_centro_dato_virtual")
 */
class CentroDatoVirtual extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $ram;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $cpu;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $hdd;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $hddSalva;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $cantidadIpReales;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $precio;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Estructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Estructura $estructura;
    /**
     * @return string|null
     */
    public function getRam(): ?string
    {
        return $this->ram;
    }

    /**
     * @param string|null $ram
     */
    public function setRam(?string $ram): void
    {
        $this->ram = $ram;
    }

    /**
     * @return string|null
     */
    public function getCpu(): ?string
    {
        return $this->cpu;
    }

    /**
     * @param string|null $cpu
     */
    public function setCpu(?string $cpu): void
    {
        $this->cpu = $cpu;
    }

    /**
     * @return string|null
     */
    public function getHdd(): ?string
    {
        return $this->hdd;
    }

    /**
     * @param string|null $hdd
     */
    public function setHdd(?string $hdd): void
    {
        $this->hdd = $hdd;
    }

    /**
     * @return string|null
     */
    public function getHddSalva(): ?string
    {
        return $this->hddSalva;
    }

    /**
     * @param string|null $hddSalva
     */
    public function setHddSalva(?string $hddSalva): void
    {
        $this->hddSalva = $hddSalva;
    }

    /**
     * @return string|null
     */
    public function getCantidadIpReales(): ?string
    {
        return $this->cantidadIpReales;
    }

    /**
     * @param string|null $cantidadIpReales
     */
    public function setCantidadIpReales(?string $cantidadIpReales): void
    {
        $this->cantidadIpReales = $cantidadIpReales;
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
     * @return Estructura|null
     */
    public function getEstructura(): ?Estructura
    {
        return $this->estructura;
    }

    /**
     * @param Estructura|null $estructura
     */
    public function setEstructura(?Estructura $estructura): void
    {
        $this->estructura = $estructura;
    }


}
