<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use App\Entity\Postgrado\RolComision;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbn_organismo_demandante")
 */
class OrganismoDemandante extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="5")
     */
    private ?string $siglas = null;


    /**
     * @ORM\ManyToOne(targetEntity="TipoOrganismo")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?TipoOrganismo $tipoOrganismo;

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
     * @return string|null
     */
    public function getSiglas(): ?string
    {
        return $this->siglas;
    }

    /**
     * @param string|null $siglas
     */
    public function setSiglas(?string $siglas): void
    {
        $this->siglas = $siglas;
    }

}
