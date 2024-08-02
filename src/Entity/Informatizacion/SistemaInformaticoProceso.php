<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use App\Entity\Informatizacion\TipoSistema;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Informatizacion\Visibilidad;
use App\Entity\Estructura\Estructura;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbr_sistema_informatico_proceso")
 */
class SistemaInformaticoProceso extends BaseEntity
{


    /**
     * @ORM\ManyToOne(targetEntity="SistemaInformatico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SistemaInformatico $sistemaInformatico;

    /**
     * @ORM\ManyToOne(targetEntity="Proceso")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Proceso $proceso;

    /**
     * @return SistemaInformatico|null
     */
    public function getSistemaInformatico(): ?SistemaInformatico
    {
        return $this->sistemaInformatico;
    }

    /**
     * @param SistemaInformatico|null $sistemaInformatico
     */
    public function setSistemaInformatico(?SistemaInformatico $sistemaInformatico): void
    {
        $this->sistemaInformatico = $sistemaInformatico;
    }

    /**
     * @return Proceso|null
     */
    public function getProceso(): ?Proceso
    {
        return $this->proceso;
    }

    /**
     * @param Proceso|null $proceso
     */
    public function setProceso(?Proceso $proceso): void
    {
        $this->proceso = $proceso;
    }


}
