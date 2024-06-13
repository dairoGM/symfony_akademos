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
 * @ORM\Table(name="convenio.tbr_convenio_accion")
 */
class ConvenioAccion extends BaseNomenclator
{

    /**
     * @ORM\ManyToOne(targetEntity="Convenio")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Convenio $convenio;

    /**
     * @return Convenio|null
     */
    public function getConvenio(): ?Convenio
    {
        return $this->convenio;
    }

    /**
     * @param Convenio|null $convenio
     */
    public function setConvenio(?Convenio $convenio): void
    {
        $this->convenio = $convenio;
    }

}
