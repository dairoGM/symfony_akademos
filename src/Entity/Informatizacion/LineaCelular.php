<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_linea_celular")
 */
class LineaCelular extends BaseEntity
{

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $numeroTelefono;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $pin;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $puk;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $planVoz;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $planSms;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $planDatos;

    /**
     * @return string|null
     */
    public function getNumeroTelefono(): ?string
    {
        return $this->numeroTelefono;
    }

    /**
     * @param string|null $numeroTelefono
     */
    public function setNumeroTelefono(?string $numeroTelefono): void
    {
        $this->numeroTelefono = $numeroTelefono;
    }

    /**
     * @return string|null
     */
    public function getPin(): ?string
    {
        return $this->pin;
    }

    /**
     * @param string|null $pin
     */
    public function setPin(?string $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @return string|null
     */
    public function getPuk(): ?string
    {
        return $this->puk;
    }

    /**
     * @param string|null $puk
     */
    public function setPuk(?string $puk): void
    {
        $this->puk = $puk;
    }

    /**
     * @return string|null
     */
    public function getPlanVoz(): ?string
    {
        return $this->planVoz;
    }

    /**
     * @param string|null $planVoz
     */
    public function setPlanVoz(?string $planVoz): void
    {
        $this->planVoz = $planVoz;
    }

    /**
     * @return string|null
     */
    public function getPlanSms(): ?string
    {
        return $this->planSms;
    }

    /**
     * @param string|null $planSms
     */
    public function setPlanSms(?string $planSms): void
    {
        $this->planSms = $planSms;
    }

    /**
     * @return string|null
     */
    public function getPlanDatos(): ?string
    {
        return $this->planDatos;
    }

    /**
     * @param string|null $planDatos
     */
    public function setPlanDatos(?string $planDatos): void
    {
        $this->planDatos = $planDatos;
    }


}
