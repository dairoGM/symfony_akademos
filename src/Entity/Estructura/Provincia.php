<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_provincia")
 * @UniqueEntity(fields="siglas", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @UniqueEntity(fields="codigo", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Provincia extends BaseNomenclator
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $codigo;

    /**
     * @ORM\Column(type="string", nullable=false, length="3")
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $siglas;

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

    public function getSiglas()
    {
        return $this->siglas;
    }

    public function setSiglas($siglas)
    {
        $this->siglas = $siglas;

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
