<?php

namespace App\Entity\Evaluacion;

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
class Solicitud extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $tipoSolicitud = null;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Institucion $institucionSolicita;


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
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\CategoriaAcreditacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?CategoriaAcreditacion $categoriaAcreditacionActual;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fechaPropuesta;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $cartaSolicitud = null;

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
    public function getInstitucionSolicita(): ?Institucion
    {
        return $this->institucionSolicita;
    }

    /**
     * @param Institucion|null $institucionSolicita
     */
    public function setInstitucionSolicita(?Institucion $institucionSolicita): void
    {
        $this->institucionSolicita = $institucionSolicita;
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
     * @return CategoriaAcreditacion|null
     */
    public function getCategoriaAcreditacionActual(): ?CategoriaAcreditacion
    {
        return $this->categoriaAcreditacionActual;
    }

    /**
     * @param CategoriaAcreditacion|null $categoriaAcreditacionActual
     */
    public function setCategoriaAcreditacionActual(?CategoriaAcreditacion $categoriaAcreditacionActual): void
    {
        $this->categoriaAcreditacionActual = $categoriaAcreditacionActual;
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


}
