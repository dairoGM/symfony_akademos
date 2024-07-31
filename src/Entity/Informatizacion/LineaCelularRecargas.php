<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\Informatizacion\LineaCelular;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbr_linea_celular_recargas")
 */
class LineaCelularRecargas extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Informatizacion\LineaCelular")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?LineaCelular $lineaCelular;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $planVoz = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $planSms = null;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $planDatos = null;

    public function __construct()
    {
        $this->planDatos = null; // Inicializar la propiedad
    }

    /**
     * @return  LineaCelular|null
     */
    public function getLineaCelular(): ?LineaCelular
    {
        return $this->lineaCelular;
    }

    /**
     * @param LineaCelular|null $lineaCelular
     */
    public function setLineaCelular(?LineaCelular $lineaCelular): void
    {
        $this->lineaCelular = $lineaCelular;
    }

    /**
     * @return string|null
     */
    public function getPlanVoz()
    {
        return $this->planVoz;
    }

    /**
     * @param string|null $planVoz
     */
    public function setPlanVoz($planVoz): void
    {
        $this->planVoz = $planVoz;
    }

    /**
     * @return string|null
     */
    public function getPlanSms()
    {
        return $this->planSms;
    }

    /**
     * @param string|null $planSms
     */
    public function setPlanSms($planSms): void
    {
        $this->planSms = $planSms;
    }

    /**
     * @return string|null
     */
    public function getPlanDatos()
    {
        return $this->planDatos;
    }

    /**
     * @param string|null $planDatos
     */
    public function setPlanDatos($planDatos): void
    {
        $this->planDatos = $planDatos;
    }


}
