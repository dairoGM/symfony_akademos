<?php

namespace App\Entity\Tramite;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Pais;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Economia\ConceptoGasto;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbr_ficha_salida_concepto_gasto")
 */
class FichaSalidaConceptoGasto extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\FichaSalida")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?FichaSalida $fichaSalida;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Economia\ConceptoGasto")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?ConceptoGasto $conceptoGasto;

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
     * @return ConceptoGasto|null
     */
    public function getConceptoGasto(): ?ConceptoGasto
    {
        return $this->conceptoGasto;
    }

    /**
     * @param ConceptoGasto|null $conceptoGasto
     */
    public function setConceptoGasto(?ConceptoGasto $conceptoGasto): void
    {
        $this->conceptoGasto = $conceptoGasto;
    }


}
