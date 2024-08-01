<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbn_servicio_contratado")
 */
class ServicioContratado extends BaseEntity
{
    /**
     * @ORM\Column(type="string", nullable=false)
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
}
