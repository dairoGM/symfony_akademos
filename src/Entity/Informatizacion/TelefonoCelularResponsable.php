<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use \App\Entity\Informatizacion\TelefonoCelular;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbr_telefono_celular_responsable")
 */
class TelefonoCelularResponsable extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="TelefonoCelular")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?TelefonoCelular $telefonoCelular;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $responsable;

    /**
     * @return \App\Entity\Informatizacion\TelefonoCelular|null
     */
    public function getTelefonoCelular(): ?\App\Entity\Informatizacion\TelefonoCelular
    {
        return $this->telefonoCelular;
    }

    /**
     * @param \App\Entity\Informatizacion\TelefonoCelular|null $telefonoCelular
     */
    public function setTelefonoCelular(?\App\Entity\Informatizacion\TelefonoCelular $telefonoCelular): void
    {
        $this->telefonoCelular = $telefonoCelular;
    }

    /**
     * @return Persona|null
     */
    public function getResponsable(): ?Persona
    {
        return $this->responsable;
    }

    /**
     * @param Persona|null $responsable
     */
    public function setResponsable(?Persona $responsable): void
    {
        $this->responsable = $responsable;
    }


}
