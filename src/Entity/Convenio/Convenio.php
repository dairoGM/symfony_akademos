<?php

namespace App\Entity\Convenio;

use App\Entity\BaseNomenclator;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Estructura\Pais;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="convenio.tbd_convenio")
 */
class Convenio extends BaseNomenclator
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio\Modalidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Modalidad $modalidad;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio\Tipo")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Tipo $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucionCubana;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\InstitucionExtranjera")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?InstitucionExtranjera $institucionExtranjera;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Pais")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Pais $pais;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaSuscribe;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCaducidad;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cantidadAcciones;

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
     * @return mixed
     */
    public function getFechaSuscribe()
    {
        return $this->fechaSuscribe;
    }

    /**
     * @param mixed $fechaSuscribe
     */
    public function setFechaSuscribe($fechaSuscribe): void
    {
        $this->fechaSuscribe = $fechaSuscribe;
    }

    /**
     * @return mixed
     */
    public function getFechaCaducidad()
    {
        return $this->fechaCaducidad;
    }

    /**
     * @param mixed $fechaCaducidad
     */
    public function setFechaCaducidad($fechaCaducidad): void
    {
        $this->fechaCaducidad = $fechaCaducidad;
    }

    /**
     * @return int|null
     */
    public function getCantidadAcciones(): ?int
    {
        return $this->cantidadAcciones;
    }

    /**
     * @param int|null $cantidadAcciones
     */
    public function setCantidadAcciones(?int $cantidadAcciones): void
    {
        $this->cantidadAcciones = $cantidadAcciones;
    }

    /**
     * @return Modalidad|null
     */
    public function getModalidad(): ?Modalidad
    {
        return $this->modalidad;
    }

    /**
     * @param Modalidad|null $modalidad
     */
    public function setModalidad(?Modalidad $modalidad): void
    {
        $this->modalidad = $modalidad;
    }

    /**
     * @return Tipo|null
     */
    public function getTipo(): ?Tipo
    {
        return $this->tipo;
    }

    /**
     * @param Tipo|null $tipo
     */
    public function setTipo(?Tipo $tipo): void
    {
        $this->tipo = $tipo;
    }


}
