<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Informatizacion\Visibilidad;
use App\Entity\Informatizacion\TipoSistema;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_sistema_informatico")
 */
class SistemaInformatico extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $direccionWeb;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $procesoImpacta;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $desarrollador;

    /**
     * @ORM\ManyToOne(targetEntity="Visibilidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Visibilidad $visibilidad;

    /**
     * @ORM\ManyToOne(targetEntity="TipoSistema")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoSistema $tipoSistema;

    /**
     * @return string|null
     */
    public function getDireccionWeb(): ?string
    {
        return $this->direccionWeb;
    }

    /**
     * @param string|null $direccionWeb
     */
    public function setDireccionWeb(?string $direccionWeb): void
    {
        $this->direccionWeb = $direccionWeb;
    }

    /**
     * @return string|null
     */
    public function getProcesoImpacta(): ?string
    {
        return $this->procesoImpacta;
    }

    /**
     * @param string|null $procesoImpacta
     */
    public function setProcesoImpacta(?string $procesoImpacta): void
    {
        $this->procesoImpacta = $procesoImpacta;
    }

    /**
     * @return string|null
     */
    public function getDesarrollador(): ?string
    {
        return $this->desarrollador;
    }

    /**
     * @param string|null $desarrollador
     */
    public function setDesarrollador(?string $desarrollador): void
    {
        $this->desarrollador = $desarrollador;
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
     * @return \App\Entity\Informatizacion\TipoSistema|null
     */
    public function getTipoSistema(): ?\App\Entity\Informatizacion\TipoSistema
    {
        return $this->tipoSistema;
    }

    /**
     * @param \App\Entity\Informatizacion\TipoSistema|null $tipoSistema
     */
    public function setTipoSistema(?\App\Entity\Informatizacion\TipoSistema $tipoSistema): void
    {
        $this->tipoSistema = $tipoSistema;
    }


}
