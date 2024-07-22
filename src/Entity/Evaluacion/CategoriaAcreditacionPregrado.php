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
 * @ORM\Table(name="evaluacion.temp_categoria_acreditacion_pregrado")
 */
class CategoriaAcreditacionPregrado extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Institucion $institucion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\CategoriaAcreditacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaAcreditacion $categoriaAcreditacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEmision;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $numeroPleno = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $numeroAcuerdoPleno = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $annosVigenciaCategoriaAcreditacion = null;

    /**
     * @return SolicitudProgramaAcademico|null
     */
    public function getSolicitudProgramaAcademico(): ?SolicitudProgramaAcademico
    {
        return $this->solicitudProgramaAcademico;
    }

    /**
     * @param SolicitudProgramaAcademico|null $solicitudProgramaAcademico
     */
    public function setSolicitudProgramaAcademico(?SolicitudProgramaAcademico $solicitudProgramaAcademico): void
    {
        $this->solicitudProgramaAcademico = $solicitudProgramaAcademico;
    }

    /**
     * @return CategoriaAcreditacion|null
     */
    public function getCategoriaAcreditacion(): ?CategoriaAcreditacion
    {
        return $this->categoriaAcreditacion;
    }

    /**
     * @param CategoriaAcreditacion|null $categoriaAcreditacion
     */
    public function setCategoriaAcreditacion(?CategoriaAcreditacion $categoriaAcreditacion): void
    {
        $this->categoriaAcreditacion = $categoriaAcreditacion;
    }

    /**
     * @return mixed
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * @param mixed $fechaEmision
     */
    public function setFechaEmision($fechaEmision): void
    {
        $this->fechaEmision = $fechaEmision;
    }

    /**
     * @return string|null
     */
    public function getNumeroPleno(): ?string
    {
        return $this->numeroPleno;
    }

    /**
     * @param string|null $numeroPleno
     */
    public function setNumeroPleno(?string $numeroPleno): void
    {
        $this->numeroPleno = $numeroPleno;
    }

    /**
     * @return string|null
     */
    public function getNumeroAcuerdoPleno(): ?string
    {
        return $this->numeroAcuerdoPleno;
    }

    /**
     * @param string|null $numeroAcuerdoPleno
     */
    public function setNumeroAcuerdoPleno(?string $numeroAcuerdoPleno): void
    {
        $this->numeroAcuerdoPleno = $numeroAcuerdoPleno;
    }

    /**
     * @return string|null
     */
    public function getAnnosVigenciaCategoriaAcreditacion(): ?string
    {
        return $this->annosVigenciaCategoriaAcreditacion;
    }

    /**
     * @param string|null $annosVigenciaCategoriaAcreditacion
     */
    public function setAnnosVigenciaCategoriaAcreditacion(?string $annosVigenciaCategoriaAcreditacion): void
    {
        $this->annosVigenciaCategoriaAcreditacion = $annosVigenciaCategoriaAcreditacion;
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


}
