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
 * @ORM\Table(name="tramite.tbd_documento_salida")
 */
class DocumentoSalida extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="FichaSalida")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?FichaSalida $fichaSalida;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $persona;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $institucionCubana;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Pais")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Pais $pais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\InstitucionExtranjera")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?InstitucionExtranjera $institucionExtranjera;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $provinciaExtranjera = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\ConceptoSalida")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?ConceptoSalida $conceptoSalida;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $objetivo = null;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaSalidaPrevista;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRegresoPrevista;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaSalidaReal;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRegresoReal;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $tiempoEstancia;


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
    private $requiereVisa = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aprobadoFactoresIes = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\EstadoFichaSalida")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?EstadoFichaSalida $estadoDocumentoSalida;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $cartaInvitacion = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaFinalizado;

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
     * @return Institucion|null
     */
    public function getInstitucionCubana(): ?Institucion
    {
        return $this->institucionCubana;
    }

    /**
     * @param Institucion|null $institucionCubana
     */
    public function setInstitucionCubana(?Institucion $institucionCubana): void
    {
        $this->institucionCubana = $institucionCubana;
    }

    /**
     * @return Pais|null
     */
    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    /**
     * @param Pais|null $pais
     */
    public function setPais(?Pais $pais): void
    {
        $this->pais = $pais;
    }

    /**
     * @return InstitucionExtranjera|null
     */
    public function getInstitucionExtranjera(): ?InstitucionExtranjera
    {
        return $this->institucionExtranjera;
    }

    /**
     * @param InstitucionExtranjera|null $institucionExtranjera
     */
    public function setInstitucionExtranjera(?InstitucionExtranjera $institucionExtranjera): void
    {
        $this->institucionExtranjera = $institucionExtranjera;
    }

    /**
     * @return string|null
     */
    public function getProvinciaExtranjera(): ?string
    {
        return $this->provinciaExtranjera;
    }

    /**
     * @param string|null $provinciaExtranjera
     */
    public function setProvinciaExtranjera(?string $provinciaExtranjera): void
    {
        $this->provinciaExtranjera = $provinciaExtranjera;
    }

    /**
     * @return ConceptoSalida|null
     */
    public function getConceptoSalida(): ?ConceptoSalida
    {
        return $this->conceptoSalida;
    }

    /**
     * @param ConceptoSalida|null $conceptoSalida
     */
    public function setConceptoSalida(?ConceptoSalida $conceptoSalida): void
    {
        $this->conceptoSalida = $conceptoSalida;
    }

    /**
     * @return string|null
     */
    public function getObjetivo(): ?string
    {
        return $this->objetivo;
    }

    /**
     * @param string|null $objetivo
     */
    public function setObjetivo(?string $objetivo): void
    {
        $this->objetivo = $objetivo;
    }

    /**
     * @return mixed
     */
    public function getFechaSalidaPrevista()
    {
        return $this->fechaSalidaPrevista;
    }

    /**
     * @param mixed $fechaSalidaPrevista
     */
    public function setFechaSalidaPrevista($fechaSalidaPrevista): void
    {
        $this->fechaSalidaPrevista = $fechaSalidaPrevista;
    }

    /**
     * @return mixed
     */
    public function getFechaRegresoPrevista()
    {
        return $this->fechaRegresoPrevista;
    }

    /**
     * @param mixed $fechaRegresoPrevista
     */
    public function setFechaRegresoPrevista($fechaRegresoPrevista): void
    {
        $this->fechaRegresoPrevista = $fechaRegresoPrevista;
    }

    /**
     * @return mixed
     */
    public function getFechaSalidaReal()
    {
        return $this->fechaSalidaReal;
    }

    /**
     * @param mixed $fechaSalidaReal
     */
    public function setFechaSalidaReal($fechaSalidaReal): void
    {
        $this->fechaSalidaReal = $fechaSalidaReal;
    }

    /**
     * @return mixed
     */
    public function getFechaRegresoReal()
    {
        return $this->fechaRegresoReal;
    }

    /**
     * @param mixed $fechaRegresoReal
     */
    public function setFechaRegresoReal($fechaRegresoReal): void
    {
        $this->fechaRegresoReal = $fechaRegresoReal;
    }

    /**
     * @return int|null
     */
    public function getTiempoEstancia(): ?int
    {
        return $this->tiempoEstancia;
    }

    /**
     * @param int|null $tiempoEstancia
     */
    public function setTiempoEstancia(?int $tiempoEstancia): void
    {
        $this->tiempoEstancia = $tiempoEstancia;
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
    public function isRequiereVisa(): bool
    {
        return $this->requiereVisa;
    }

    /**
     * @param bool $requiereVisa
     */
    public function setRequiereVisa(bool $requiereVisa): void
    {
        $this->requiereVisa = $requiereVisa;
    }

    /**
     * @return bool
     */
    public function isAprobadoFactoresIes(): bool
    {
        return $this->aprobadoFactoresIes;
    }

    /**
     * @param bool $aprobadoFactoresIes
     */
    public function setAprobadoFactoresIes(bool $aprobadoFactoresIes): void
    {
        $this->aprobadoFactoresIes = $aprobadoFactoresIes;
    }

    /**
     * @return EstadoFichaSalida|null
     */
    public function getEstadoDocumentoSalida(): ?EstadoFichaSalida
    {
        return $this->estadoDocumentoSalida;
    }

    /**
     * @param EstadoFichaSalida|null $estadoDocumentoSalida
     */
    public function setEstadoDocumentoSalida(?EstadoFichaSalida $estadoDocumentoSalida): void
    {
        $this->estadoDocumentoSalida = $estadoDocumentoSalida;
    }

    /**
     * @return string|null
     */
    public function getCartaInvitacion(): ?string
    {
        return $this->cartaInvitacion;
    }

    /**
     * @param string|null $cartaInvitacion
     */
    public function setCartaInvitacion(?string $cartaInvitacion): void
    {
        $this->cartaInvitacion = $cartaInvitacion;
    }

    /**
     * @return FichaSalida|null
     */
    public function getFichaSalida(): ?FichaSalida
    {
        return $this->fichaSalida;
    }

    /**
     * @param FichaSalida|null $fichaSalida
     */
    public function setFichaSalida(?FichaSalida $fichaSalida): void
    {
        $this->fichaSalida = $fichaSalida;
    }

    /**
     * @return mixed
     */
    public function getFechaFinalizado()
    {
        return $this->fechaFinalizado;
    }

    /**
     * @param mixed $fechaFinalizado
     */
    public function setFechaFinalizado($fechaFinalizado): void
    {
        $this->fechaFinalizado = $fechaFinalizado;
    }


}
