<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_solicitud_programa_dictamen")
 */
class SolicitudProgramaDictamen extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Comision")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Comision $comision = null;

    /**
     * @ORM\ManyToOne(targetEntity="SolicitudPrograma")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudPrograma $solicitudPrograma = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamen = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona" )
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $propietarioDictamen;

    /**
     * @ORM\ManyToOne(targetEntity="RolComision")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?RolComision $rolComision;


    public function getRolComision()
    {
        return $this->rolComision;
    }

    public function setRolComision($rolComision)
    {
        $this->rolComision = $rolComision;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDictamen(): ?string
    {
        return $this->dictamen;
    }

    /**
     * @param string|null $dictamen
     */
    public function setDictamen(?string $dictamen): void
    {
        $this->dictamen = $dictamen;
    }


    public function getComision()
    {
        return $this->comision;
    }

    public function setComision($comision)
    {
        $this->comision = $comision;

        return $this;
    }


    public function getSolicitudPrograma()
    {
        return $this->solicitudPrograma;
    }

    public function setSolicitudPrograma($solicitudPrograma)
    {
        $this->solicitudPrograma = $solicitudPrograma;

        return $this;
    }

    /**
     * @return Persona|null
     */
    public function getPropietarioDictamen(): ?Persona
    {
        return $this->propietarioDictamen;
    }

    /**
     * @param Persona|null $propietarioDictamen
     */
    public function setPropietarioDictamen(?Persona $propietarioDictamen): void
    {
        $this->propietarioDictamen = $propietarioDictamen;
    }


}
