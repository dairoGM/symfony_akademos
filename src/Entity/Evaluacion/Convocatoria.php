<?php

namespace App\Entity\Evaluacion;

use App\Entity\BaseNomenclator;
use App\Entity\Convenio\Modalidad;
use App\Entity\Convenio\Tipo;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Estructura\Pais;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion.tbd_convocatoria")
 */
class Convocatoria extends BaseNomenclator
{

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaInicio;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $carta = null;

    /**
     * @return mixed
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * @param mixed $fechaInicio
     */
    public function setFechaInicio($fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return mixed
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * @param mixed $fechaFin
     */
    public function setFechaFin($fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * @return string|null
     */
    public function getCarta(): ?string
    {
        return $this->carta;
    }

    /**
     * @param string|null $carta
     */
    public function setCarta(?string $carta): void
    {
        $this->carta = $carta;
    }


}
