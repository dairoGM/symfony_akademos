<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_municipio")
 * @UniqueEntity(fields="codigo", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Municipio extends BaseNomenclator
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $codigo;    

    /**
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Provincia $provincia;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $extId;

    
    public function getCodigo()
    {
        return $this->codigo;
    }
   
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }

    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }
   
    public function getExtId()
    {
        return $this->extId;
    }
   
    public function setExtId($extId)
    {
        $this->extId = $extId;

        return $this;
    }
}