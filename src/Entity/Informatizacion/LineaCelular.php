<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_linea_celular")
 * @UniqueEntity(fields="numeroTelefono", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @UniqueEntity(fields="puk", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
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
