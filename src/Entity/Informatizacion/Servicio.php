<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseNomenclator;
use App\Entity\Estructura\Estructura;
use App\Entity\Informatizacion\TipoConectividad;
use App\Entity\Informatizacion\PublicoObjetivo;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Informatizacion\Visibilidad;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_servicio")
 */
class Servicio extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $clasificacion;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $direccionWeb;
    /**
     * @ORM\ManyToOne(targetEntity="Visibilidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Visibilidad $visibilidad;

    /**
     * @ORM\ManyToOne(targetEntity="PublicoObjetivo")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?PublicoObjetivo $publicoObjetivo;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Estructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Estructura $estructura;

    /**
     * @return string|null
     */
    public function getDireccionWeb(): ?string
    {
        return $this->direccionWeb;
    }

    /**
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private ?bool $integracionPasarelaPago = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $integracionFirmaDigital = false;

    /**
     * @param string|null $direccionWeb
     */
    public function setDireccionWeb(?string $direccionWeb): void
    {
        $this->direccionWeb = $direccionWeb;
    }

    /**
     * @return \App\Entity\Informatizacion\Visibilidad|null
     */
    public function getVisibilidad(): ?\App\Entity\Informatizacion\Visibilidad
    {
        return $this->visibilidad;
    }

    /**
     * @param \App\Entity\Informatizacion\Visibilidad|null $visibilidad
     */
    public function setVisibilidad(?\App\Entity\Informatizacion\Visibilidad $visibilidad): void
    {
        $this->visibilidad = $visibilidad;
    }

    /**
     * @return \App\Entity\Informatizacion\PublicoObjetivo|null
     */
    public function getPublicoObjetivo(): ?\App\Entity\Informatizacion\PublicoObjetivo
    {
        return $this->publicoObjetivo;
    }

    /**
     * @param \App\Entity\Informatizacion\PublicoObjetivo|null $publicoObjetivo
     */
    public function setPublicoObjetivo(?\App\Entity\Informatizacion\PublicoObjetivo $publicoObjetivo): void
    {
        $this->publicoObjetivo = $publicoObjetivo;
    }

    /**
     * @return bool|null
     */
    public function getIntegracionPasarelaPago(): ?bool
    {
        return $this->integracionPasarelaPago;
    }

    /**
     * @param bool|null $integracionPasarelaPago
     */
    public function setIntegracionPasarelaPago(?bool $integracionPasarelaPago): void
    {
        $this->integracionPasarelaPago = $integracionPasarelaPago;
    }

    /**
     * @return bool|null
     */
    public function getIntegracionFirmaDigital(): ?bool
    {
        return $this->integracionFirmaDigital;
    }

    /**
     * @param bool|null $integracionFirmaDigital
     */
    public function setIntegracionFirmaDigital(?bool $integracionFirmaDigital): void
    {
        $this->integracionFirmaDigital = $integracionFirmaDigital;
    }

    /**
     * @return string|null
     */
    public function getClasificacion(): ?string
    {
        return $this->clasificacion;
    }

    /**
     * @param string|null $clasificacion
     */
    public function setClasificacion(?string $clasificacion): void
    {
        $this->clasificacion = $clasificacion;
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
