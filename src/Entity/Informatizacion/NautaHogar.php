<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbd_nauta_hogar")
 */
class NautaHogar extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $responsable;


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
    private ?string $servicioContratado;

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
    public function getServicioContratado(): ?string
    {
        return $this->servicioContratado;
    }

    /**
     * @param string|null $servicioContratado
     */
    public function setServicioContratado(?string $servicioContratado): void
    {
        $this->servicioContratado = $servicioContratado;
    }

}