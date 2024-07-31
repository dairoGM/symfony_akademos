<?php

namespace App\Entity\Informatizacion;

use App\Entity\BaseEntity;
use \App\Entity\Informatizacion\LineaCelular;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="informatizacion.tbr_linea_celular_responsable")
 */
class LineaCelularResponsable extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="LineaCelular")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?LineaCelular $lineaCelular;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Persona $responsable;

    /**
     * @return \App\Entity\Informatizacion\LineaCelular|null
     */
    public function getLineaCelular(): ?\App\Entity\Informatizacion\LineaCelular
    {
        return $this->lineaCelular;
    }

    /**
     * @param \App\Entity\Informatizacion\LineaCelular|null $lineaCelular
     */
    public function setLineaCelular(?\App\Entity\Informatizacion\LineaCelular $lineaCelular): void
    {
        $this->lineaCelular = $lineaCelular;
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
