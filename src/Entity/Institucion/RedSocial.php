<?php

namespace App\Entity\Institucion;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbn_red_social")
 */
class RedSocial extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true, length="10")
     */
    private ?string $logo = null;


    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }
}

