<?php

namespace App\Entity\Tramite;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Pais;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Institucion\Institucion;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbd_pasaporte")
 */
class Pasaporte extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Persona $persona;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\TipoPasaporte")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoPasaporte $tipoPasaporte;


    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $numeroPasaporte;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEmisionPasaporte;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCaducidadPasaporte;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activo = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cara1 = null;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cara2 = null;

    /**
     * @return Persona|null
     */
    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    /**
     * @param Persona|null $persona
     */
    public function setPersona(?Persona $persona): void
    {
        $this->persona = $persona;
    }

    /**
     * @return TipoPasaporte|null
     */
    public function getTipoPasaporte(): ?TipoPasaporte
    {
        return $this->tipoPasaporte;
    }

    /**
     * @param TipoPasaporte|null $tipoPasaporte
     */
    public function setTipoPasaporte(?TipoPasaporte $tipoPasaporte): void
    {
        $this->tipoPasaporte = $tipoPasaporte;
    }

    /**
     * @return string|null
     */
    public function getNumeroPasaporte(): ?string
    {
        return $this->numeroPasaporte;
    }

    /**
     * @param string|null $numeroPasaporte
     */
    public function setNumeroPasaporte(?string $numeroPasaporte): void
    {
        $this->numeroPasaporte = $numeroPasaporte;
    }

    /**
     * @return mixed
     */
    public function getFechaEmisionPasaporte()
    {
        return $this->fechaEmisionPasaporte;
    }

    /**
     * @param mixed $fechaEmisionPasaporte
     */
    public function setFechaEmisionPasaporte($fechaEmisionPasaporte): void
    {
        $this->fechaEmisionPasaporte = $fechaEmisionPasaporte;
    }

    /**
     * @return mixed
     */
    public function getFechaCaducidadPasaporte()
    {
        return $this->fechaCaducidadPasaporte;
    }

    /**
     * @param mixed $fechaCaducidadPasaporte
     */
    public function setFechaCaducidadPasaporte($fechaCaducidadPasaporte): void
    {
        $this->fechaCaducidadPasaporte = $fechaCaducidadPasaporte;
    }

    /**
     * @return bool
     */
    public function isActivo(): bool
    {
        return $this->activo;
    }

    /**
     * @param bool $activo
     */
    public function setActivo(bool $activo): void
    {
        $this->activo = $activo;
    }

    /**
     * @return null
     */
    public function getCara1()
    {
        return $this->cara1;
    }

    /**
     * @param null $cara1
     */
    public function setCara1($cara1): void
    {
        $this->cara1 = $cara1;
    }

    /**
     * @return null
     */
    public function getCara2()
    {
        return $this->cara2;
    }

    /**
     * @param null $cara2
     */
    public function setCara2($cara2): void
    {
        $this->cara2 = $cara2;
    }


}
