<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;

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
     * @ORM\JoinColumn(nullable=false)
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
    private $fechaAprobacionn;


    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?integer $duracionCursoDiurno = null;


    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?integer $duracionCursoPorEncuentro = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamen = null;

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
    public function getFechaAprobacionn()
    {
        return $this->fechaAprobacionn;
    }

    /**
     * @param mixed $fechaAprobacionn
     */
    public function setFechaAprobacionn($fechaAprobacionn): void
    {
        $this->fechaAprobacionn = $fechaAprobacionn;
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


}
