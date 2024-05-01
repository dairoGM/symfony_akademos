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
 * @ORM\Table(name="tramite.tbr_ficha_salida_estado")
 */
class FichaSalidaEstado extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\FichaSalida")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?FichaSalida $fichaSalida;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\EstadoFichaSalida")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?EstadoFichaSalida $estadoFichaSalida;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcion = null;

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
     * @return EstadoFichaSalida|null
     */
    public function getEstadoFichaSalida(): ?EstadoFichaSalida
    {
        return $this->estadoFichaSalida;
    }

    /**
     * @param EstadoFichaSalida|null $estadoFichaSalida
     */
    public function setEstadoFichaSalida(?EstadoFichaSalida $estadoFichaSalida): void
    {
        $this->estadoFichaSalida = $estadoFichaSalida;
    }

    /**
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string|null $descripcion
     */
    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

}
