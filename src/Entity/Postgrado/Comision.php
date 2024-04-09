<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbd_comision")
 */
class Comision extends BaseNomenclator
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\TipoComision")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoComision $tipoComision;

    /**
     * @return TipoComision|null
     */
    public function getTipoComision(): ?TipoComision
    {
        return $this->tipoComision;
    }

    /**
     * @param TipoComision|null $tipoComision
     */
    public function setTipoComision(?TipoComision $tipoComision): void
    {
        $this->tipoComision = $tipoComision;
    }



}
