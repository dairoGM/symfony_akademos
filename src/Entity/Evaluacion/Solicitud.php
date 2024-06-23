<?php

namespace App\Entity\Evaluacion;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion.tbd_solicitud")
 */
class Solicitud extends BaseEntity
{
    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $tipoSolicitud = null;

    /**
     * @ORM\ManyToOne(targetEntity="Convocatoria")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Convocatoria $convocatoria;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?SolicitudProgramaAcademico $programaPregrado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\SolicitudPrograma")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?SolicitudPrograma $programaPosgrado;

    /**
     * @ORM\ManyToOne(targetEntity="EstadoSolicitud")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?EstadoSolicitud $estadoSolicitud;


    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fechaPropuesta;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAprobada;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $cartaSolicitud = null;
    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $categoriaAcreditacionActual = null;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $motivoRechazo = null;

    /**
     * @return string|null
     */
    public function getTipoSolicitud(): ?string
    {
        return $this->tipoSolicitud;
    }

    /**
     * @param string|null $tipoSolicitud
     */
    public function setTipoSolicitud(?string $tipoSolicitud): void
    {
        $this->tipoSolicitud = $tipoSolicitud;
    }

    /**
     * @return Institucion|null
     */
    public function getInstitucion(): ?Institucion
    {
        return $this->institucion;
    }

    /**
     * @param Institucion|null $institucion
     */
    public function setInstitucion(?Institucion $institucion): void
    {
        $this->institucion = $institucion;
    }

    /**
     * @return SolicitudProgramaAcademico|null
     */
    public function getProgramaPregrado(): ?SolicitudProgramaAcademico
    {
        return $this->programaPregrado;
    }

    /**
     * @param SolicitudProgramaAcademico|null $programaPregrado
     */
    public function setProgramaPregrado(?SolicitudProgramaAcademico $programaPregrado): void
    {
        $this->programaPregrado = $programaPregrado;
    }

    /**
     * @return SolicitudPrograma|null
     */
    public function getProgramaPosgrado(): ?SolicitudPrograma
    {
        return $this->programaPosgrado;
    }

    /**
     * @param SolicitudPrograma|null $programaPosgrado
     */
    public function setProgramaPosgrado(?SolicitudPrograma $programaPosgrado): void
    {
        $this->programaPosgrado = $programaPosgrado;
    }

    /**
     * @return \App\Entity\Evaluacion\EstadoSolicitud|null
     */
    public function getEstadoSolicitud(): ?\App\Entity\Evaluacion\EstadoSolicitud
    {
        return $this->estadoSolicitud;
    }

    /**
     * @param \App\Entity\Evaluacion\EstadoSolicitud|null $estadoSolicitud
     */
    public function setEstadoSolicitud(?\App\Entity\Evaluacion\EstadoSolicitud $estadoSolicitud): void
    {
        $this->estadoSolicitud = $estadoSolicitud;
    }


    /**
     * @return mixed
     */
    public function getFechaPropuesta()
    {
        return $this->fechaPropuesta;
    }

    /**
     * @param mixed $fechaPropuesta
     */
    public function setFechaPropuesta($fechaPropuesta): void
    {
        $this->fechaPropuesta = $fechaPropuesta;
    }

    /**
     * @return string|null
     */
    public function getCartaSolicitud(): ?string
    {
        return $this->cartaSolicitud;
    }

    /**
     * @param string|null $cartaSolicitud
     */
    public function setCartaSolicitud(?string $cartaSolicitud): void
    {
        $this->cartaSolicitud = $cartaSolicitud;
    }

    /**
     * @return Convocatoria|null
     */
    public function getConvocatoria(): ?Convocatoria
    {
        return $this->convocatoria;
    }

    /**
     * @param Convocatoria|null $convocatoria
     */
    public function setConvocatoria(?Convocatoria $convocatoria): void
    {
        $this->convocatoria = $convocatoria;
    }

    /**
     * @return mixed
     */
    public function getFechaAprobada()
    {
        return $this->fechaAprobada;
    }

    /**
     * @param mixed $fechaAprobada
     */
    public function setFechaAprobada($fechaAprobada): void
    {
        $this->fechaAprobada = $fechaAprobada;
    }

    /**
     * @return string|null
     */
    public function getCategoriaAcreditacionActual(): ?string
    {
        return $this->categoriaAcreditacionActual;
    }

    /**
     * @param string|null $categoriaAcreditacionActual
     */
    public function setCategoriaAcreditacionActual(?string $categoriaAcreditacionActual): void
    {
        $this->categoriaAcreditacionActual = $categoriaAcreditacionActual;
    }

    /**
     * @return string|null
     */
    public function getMotivoRechazo(): ?string
    {
        return $this->motivoRechazo;
    }

    /**
     * @param string|null $motivoRechazo
     */
    public function setMotivoRechazo(?string $motivoRechazo): void
    {
        $this->motivoRechazo = $motivoRechazo;
    }


}
