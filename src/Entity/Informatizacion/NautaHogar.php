<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_nauta_hogar")
 * @UniqueEntity(fields="numeroTelefono", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class NautaHogar extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $responsable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Informatizacion\ServicioContratado")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?ServicioContratado $servicioContratado;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $numeroTelefono;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $direccionInstalacion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $precio;

    /**
     * @return Persona|null
     */
    public function getResponsable(): ?Persona
    {
        return $this->responsable;
    }

    /**
     * @param Persona|null $responsable
     */
    public function setResponsable(?Persona $responsable): void
    {
        $this->responsable = $responsable;
    }

    /**
     * @return ServicioContratado|null
     */
    public function getServicioContratado(): ?ServicioContratado
    {
        return $this->servicioContratado;
    }

    /**
     * @param ServicioContratado|null $servicioContratado
     */
    public function setServicioContratado(?ServicioContratado $servicioContratado): void
    {
        $this->servicioContratado = $servicioContratado;
    }

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
    public function getDireccionInstalacion(): ?string
    {
        return $this->direccionInstalacion;
    }

    /**
     * @param string|null $direccionInstalacion
     */
    public function setDireccionInstalacion(?string $direccionInstalacion): void
    {
        $this->direccionInstalacion = $direccionInstalacion;
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


}
