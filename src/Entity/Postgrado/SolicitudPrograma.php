<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbd_solicitud_programa")
 */
class SolicitudPrograma extends BaseEntity
{
    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Regex(
     *           pattern= "/^[,0-9a-zA-ZäëïöüáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $nombre = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcion = null;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\TipoSolicitud")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?TipoSolicitud $tipoSolicitud;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\TipoSolicitudClasificacion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?TipoSolicitudClasificacion $tipoSolicitudClasificacion;

    /**
     * @ORM\ManyToOne(targetEntity="EstadoPrograma")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?EstadoPrograma $estadoPrograma;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $universidad = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $originalDe = null;

    /**
     * @ORM\ManyToOne(targetEntity="TipoPrograma")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?TipoPrograma $tipoPrograma;

    /**
     * @ORM\ManyToOne(targetEntity="RamaCiencia")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?RamaCiencia $ramaCiencia;


    /**
     * @ORM\ManyToOne(targetEntity="ModalidadPrograma")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?ModalidadPrograma $modalidadPrograma;


    /**
     * @ORM\ManyToOne(targetEntity="PresencialidadPrograma")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
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
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $telefonoCoordinador = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="50")
     */
    private ?string $codigoPrograma = null;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\CategoriaAcreditacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaAcreditacion $categoriaAcreditacion = null;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaProximaAcreditacion;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAprobacion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $duracionPrograma = 0;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cantidadCreditos = 0;


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
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamenGeneral = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamenFinal = null;

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

    /**
     * @return string|null
     */
    public function getDictamenGeneral(): ?string
    {
        return $this->dictamenGeneral;
    }

    /**
     * @param string|null $dictamenGeneral
     */
    public function setDictamenGeneral(?string $dictamenGeneral): void
    {
        $this->dictamenGeneral = $dictamenGeneral;
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

    public function getDictamenFinal()
    {
        return $this->dictamenFinal;
    }

    public function setDictamenFinal($dictamenFinal)
    {
        $this->dictamenFinal = $dictamenFinal;

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


    public function getCategoriaAcreditacion()
    {
        return $this->categoriaAcreditacion;
    }

    public function setCategoriaAcreditacion($categoriaAcreditacion)
    {
        $this->categoriaAcreditacion = $categoriaAcreditacion;

        return $this;
    }

    /**
     * @return Institucion|null
     */
    public function getUniversidad(): ?Institucion
    {
        return $this->universidad;
    }

    /**
     * @param Institucion|null $universidad
     */
    public function setUniversidad(?Institucion $universidad): void
    {
        $this->universidad = $universidad;
    }

    /**
     * @return Institucion|null
     */
    public function getOriginalDe(): ?Institucion
    {
        return $this->originalDe;
    }

    /**
     * @param Institucion|null $originalDe
     */
    public function setOriginalDe(?Institucion $originalDe): void
    {
        $this->originalDe = $originalDe;
    }

    /**
     * @return TipoSolicitud|null
     */
    public function getTipoSolicitud(): ?TipoSolicitud
    {
        return $this->tipoSolicitud;
    }

    /**
     * @param TipoSolicitud|null $tipoSolicitud
     */
    public function setTipoSolicitud(?TipoSolicitud $tipoSolicitud): void
    {
        $this->tipoSolicitud = $tipoSolicitud;
    }

    /**
     * @return TipoSolicitudClasificacion|null
     */
    public function getTipoSolicitudClasificacion(): ?TipoSolicitudClasificacion
    {
        return $this->tipoSolicitudClasificacion;
    }

    /**
     * @param TipoSolicitudClasificacion|null $tipoSolicitudClasificacion
     */
    public function setTipoSolicitudClasificacion(?TipoSolicitudClasificacion $tipoSolicitudClasificacion): void
    {
        $this->tipoSolicitudClasificacion = $tipoSolicitudClasificacion;
    }


    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDuracionPrograma()
    {
        return $this->duracionPrograma;
    }

    /**
     * @param int|null $duracionPrograma
     */
    public function setDuracionPrograma(?int $duracionPrograma): void
    {
        $this->duracionPrograma = $duracionPrograma;
    }


    /**
     * @return int|null
     */
    public function getCantidadCreditos(): ?int
    {
        return $this->cantidadCreditos;
    }

    /**
     * @param int|null $cantidadCreditos
     */
    public function setCantidadCreditos(?int $cantidadCreditos): void
    {
        $this->cantidadCreditos = $cantidadCreditos;
    }

    /**
     * @return mixed
     */
    public function getFechaAprobacion()
    {
        return $this->fechaAprobacion;
    }

    /**
     * @param mixed $fechaAprobacion
     */
    public function setFechaAprobacion($fechaAprobacion): void
    {
        $this->fechaAprobacion = $fechaAprobacion;
    }


}
