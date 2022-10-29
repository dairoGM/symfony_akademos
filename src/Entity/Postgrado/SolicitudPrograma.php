<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbd_solicitud_programa")
 */
class SolicitudPrograma extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="EstadoPrograma")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?EstadoPrograma $estadoPrograma;

    /**
     * @ORM\ManyToOne(targetEntity="Comision")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Comision $comision;

    /**
     * @ORM\ManyToOne(targetEntity="Universidad")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Universidad $universidad;

    /**
     * @ORM\ManyToOne(targetEntity="TipoPrograma")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TipoPrograma $tipoPrograma;

    /**
     * @ORM\ManyToOne(targetEntity="RamaCiencia")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?RamaCiencia $ramaCiencia;


    /**
     * @ORM\ManyToOne(targetEntity="ModalidadPrograma")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ModalidadPrograma $modalidadPrograma;


    /**
     * @ORM\ManyToOne(targetEntity="PresencialidadPrograma")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?PresencialidadPrograma $presencialidadPrograma;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombreCoordinador = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $correoCoordinador = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $telefonoCoordinador = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="50")
     */
    private ?string $codigoPrograma = null;

    /**
     * @ORM\ManyToOne(targetEntity="NivelAcreditacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?NivelAcreditacion $nivelAcreditacion;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaCategorizacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaCategorizacion $categoriaCategorizacion;
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $fechaProximaAcreditacion;

    public function getNivelAcreditacion()
    {
        return $this->nivelAcreditacion;
    }

    public function setNivelAcreditacion($nivelAcreditacion)
    {
        $this->nivelAcreditacion = $nivelAcreditacion;

        return $this;
    }


    public function getFechaProximaAcreditacion()
    {
        return $this->fechaProximaAcreditacion;
    }

    public function setFechaProximaAcreditacion($fechaProximaAcreditacion)
    {
        $this->fechaProximaAcreditacion = $fechaProximaAcreditacion;

        return $this;
    }


    public function getCodigoPrograma()
    {
        return $this->codigoPrograma;
    }

    public function setCodigoPrograma($codigoPrograma)
    {
        $this->codigoPrograma = $codigoPrograma;

        return $this;
    }


    public function getTelefonoCoordinador()
    {
        return $this->telefonoCoordinador;
    }

    public function setTelefonoCoordinador($telefonoCoordinador)
    {
        $this->telefonoCoordinador = $telefonoCoordinador;

        return $this;
    }


    public function getCorreoCoordinador()
    {
        return $this->correoCoordinador;
    }

    public function setCorreoCoordinador($correoCoordinador)
    {
        $this->correoCoordinador = $correoCoordinador;

        return $this;
    }


    public function getNombreCoordinador()
    {
        return $this->nombreCoordinador;
    }

    public function setNombreCoordinador($nombreCoordinador)
    {
        $this->nombreCoordinador = $nombreCoordinador;

        return $this;
    }


    public function getPresencialidadPrograma()
    {
        return $this->presencialidadPrograma;
    }

    public function setPresencialidadPrograma($presencialidadPrograma)
    {
        $this->presencialidadPrograma = $presencialidadPrograma;

        return $this;
    }


    public function getModalidadPrograma()
    {
        return $this->modalidadPrograma;
    }

    public function setModalidadPrograma($modalidadPrograma)
    {
        $this->modalidadPrograma = $modalidadPrograma;

        return $this;
    }

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $docPrograma = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $resolucionPrograma = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="4")
     */
    private ?string $annoAcreditacion = null;


    public function getAnnoAcreditacion()
    {
        return $this->annoAcreditacion;
    }

    public function setAnnoAcreditacion($annoAcreditacion)
    {
        $this->annoAcreditacion = $annoAcreditacion;

        return $this;
    }


    public function getResolucionPrograma()
    {
        return $this->resolucionPrograma;
    }

    public function setResolucionPrograma($resolucionPrograma)
    {
        $this->resolucionPrograma = $resolucionPrograma;

        return $this;
    }


    public function getRamaCiencia()
    {
        return $this->ramaCiencia;
    }

    public function setRamaCiencia($ramaCiencia)
    {
        $this->ramaCiencia = $ramaCiencia;

        return $this;
    }


    public function getTipoPrograma()
    {
        return $this->tipoPrograma;
    }

    public function setTipoPrograma($tipoPrograma)
    {
        $this->tipoPrograma = $tipoPrograma;

        return $this;
    }


    public function getUniversidad()
    {
        return $this->universidad;
    }

    public function setUniversidad($universidad)
    {
        $this->universidad = $universidad;

        return $this;
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

    public function getDocPrograma()
    {
        return $this->docPrograma;
    }

    public function setDocPrograma($docPrograma)
    {
        $this->docPrograma = $docPrograma;

        return $this;
    }


    public function getEstadoPrograma()
    {
        return $this->estadoPrograma;
    }

    public function setEstadoPrograma($estadoPrograma)
    {
        $this->estadoPrograma = $estadoPrograma;

        return $this;
    }
    public function getCategoriaCategorizacion()
    {
        return $this->categoriaCategorizacion;
    }

    public function setCategoriaCategorizacion($categoriaCategorizacion)
    {
        $this->categoriaCategorizacion = $categoriaCategorizacion;

        return $this;
    }
}
