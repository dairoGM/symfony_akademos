<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_recurso_humano")
 */
class InstitucionRecursoHumano extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $institucion;


    /**
     * @ORM\ManyToOne(targetEntity="RecursosHumanos")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?RecursosHumanos $recursoHumano;

    /**
     * @ORM\Column(type="integer", nullable=false, length="100")
     */
    private ?string $cantidad = null;


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
     * @return Institucion|null
     */
    public function getRecursoHumano(): ?RecursosHumanos
    {
        return $this->recursoHumano;
    }

    /**
     * @param Institucion|null $recursoHumano
     */
    public function setRecursoHumano(?RecursosHumanos $recursoHumano): void
    {
        $this->recursoHumano = $recursoHumano;
    }

    /**
     * @return string|null
     */
    public function getCantidad(): ?string
    {
        return $this->cantidad;
    }

    /**
     * @param string|null $cantidad
     */
    public function setCantidad(?string $cantidad): void
    {
        $this->cantidad = $cantidad;
    }





}
