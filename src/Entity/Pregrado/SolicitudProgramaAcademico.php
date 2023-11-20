<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbd_solicitud_programa_academico")
 */
class SolicitudProgramaAcademico extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="EstadoProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?EstadoProgramaAcademico $estadoProgramaAcademico;


    /**
     * @ORM\ManyToOne(targetEntity="TipoProgramaAcademico")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TipoProgramaAcademico $tipoProgramaAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="TipoOrganismo")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoOrganismo $tipoOrganismo;


    /**
     * @ORM\ManyToOne(targetEntity="OrganismoDemandante")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OrganismoDemandante $organismoDemandante;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $fundamentacion = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $resolucion = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $cartaAprobacion = null;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAprobacion;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-Záéíóúàè.ìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $descripcionAprobacion = null;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-Záéíóúàè.ìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $descripcionNoAprobacion = null;

    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoDiurno = null;


    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoPorEncuentro = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamen = null;


    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?\App\Entity\Institucion\Institucion $centroRector;

    /**
     * @return EstadoProgramaAcademico|null
     */
    public function getEstadoProgramaAcademico(): ?EstadoProgramaAcademico
    {
        return $this->estadoProgramaAcademico;
    }

    /**
     * @param EstadoProgramaAcademico|null $estadoProgramaAcademico
     */
    public function setEstadoProgramaAcademico(?EstadoProgramaAcademico $estadoProgramaAcademico): void
    {
        $this->estadoProgramaAcademico = $estadoProgramaAcademico;
    }

    /**
     * @return TipoProgramaAcademico|null
     */
    public function getTipoProgramaAcademico(): ?TipoProgramaAcademico
    {
        return $this->tipoProgramaAcademico;
    }

    /**
     * @param TipoProgramaAcademico|null $tipoProgramaAcademico
     */
    public function setTipoProgramaAcademico(?TipoProgramaAcademico $tipoProgramaAcademico): void
    {
        $this->tipoProgramaAcademico = $tipoProgramaAcademico;
    }

    /**
     * @return TipoOrganismo|null
     */
    public function getTipoOrganismo(): ?TipoOrganismo
    {
        return $this->tipoOrganismo;
    }

    /**
     * @param TipoOrganismo|null $tipoOrganismo
     */
    public function setTipoOrganismo(?TipoOrganismo $tipoOrganismo): void
    {
        $this->tipoOrganismo = $tipoOrganismo;
    }

    /**
     * @return OrganismoDemandante|null
     */
    public function getOrganismoDemandante(): ?OrganismoDemandante
    {
        return $this->organismoDemandante;
    }

    /**
     * @param OrganismoDemandante|null $organismoDemandante
     */
    public function setOrganismoDemandante(?OrganismoDemandante $organismoDemandante): void
    {
        $this->organismoDemandante = $organismoDemandante;
    }

    /**
     * @return string|null
     */
    public function getFundamentacion(): ?string
    {
        return $this->fundamentacion;
    }

    /**
     * @param string|null $fundamentacion
     */
    public function setFundamentacion(?string $fundamentacion): void
    {
        $this->fundamentacion = $fundamentacion;
    }

    /**
     * @return string|null
     */
    public function getResolucion(): ?string
    {
        return $this->resolucion;
    }

    /**
     * @param string|null $resolucion
     */
    public function setResolucion(?string $resolucion): void
    {
        $this->resolucion = $resolucion;
    }

    /**
     * @return string|null
     */
    public function getCartaAprobacion(): ?string
    {
        return $this->cartaAprobacion;
    }

    /**
     * @param string|null $cartaAprobacion
     */
    public function setCartaAprobacion(?string $cartaAprobacion): void
    {
        $this->cartaAprobacion = $cartaAprobacion;
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

    /**
     * @return int|null
     */
    public function getDuracionCursoDiurno(): ?int
    {
        return $this->duracionCursoDiurno;
    }

    /**
     * @param int|null $duracionCursoDiurno
     */
    public function setDuracionCursoDiurno(?int $duracionCursoDiurno): void
    {
        $this->duracionCursoDiurno = $duracionCursoDiurno;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoPorEncuentro(): ?int
    {
        return $this->duracionCursoPorEncuentro;
    }

    /**
     * @param int|null $duracionCursoPorEncuentro
     */
    public function setDuracionCursoPorEncuentro(?int $duracionCursoPorEncuentro): void
    {
        $this->duracionCursoPorEncuentro = $duracionCursoPorEncuentro;
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

    /**
     * @return string|null
     */
    public function getDescripcionAprobacion(): ?string
    {
        return $this->descripcionAprobacion;
    }

    /**
     * @param string|null $descripcionAprobacion
     */
    public function setDescripcionAprobacion(?string $descripcionAprobacion): void
    {
        $this->descripcionAprobacion = $descripcionAprobacion;
    }

    /**
     * @return string|null
     */
    public function getDescripcionNoAprobacion(): ?string
    {
        return $this->descripcionNoAprobacion;
    }

    /**
     * @param string|null $descripcionNoAprobacion
     */
    public function setDescripcionNoAprobacion(?string $descripcionNoAprobacion): void
    {
        $this->descripcionNoAprobacion = $descripcionNoAprobacion;
    }

    /**
     * @return \App\Entity\Institucion\Institucion|null
     */
    public function getCentroRector(): ?\App\Entity\Institucion\Institucion
    {
        return $this->centroRector;
    }

    /**
     * @param \App\Entity\Institucion\Institucion|null $centroRector
     */
    public function setCentroRector(?\App\Entity\Institucion\Institucion $centroRector): void
    {
        $this->centroRector = $centroRector;
    }


}
