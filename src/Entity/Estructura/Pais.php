<?php

namespace App\Entity\Estructura;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbn_pais")
 * @UniqueEntity(fields="siglas", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @UniqueEntity(fields="codigo", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Pais extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $iso2;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $iso3;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $codigoTelefonico;

    /**
     * @return string|null
     */
    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    /**
     * @param string|null $iso2
     */
    public function setIso2(?string $iso2): void
    {
        $this->iso2 = $iso2;
    }

    /**
     * @return string|null
     */
    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    /**
     * @param string|null $iso3
     */
    public function setIso3(?string $iso3): void
    {
        $this->iso3 = $iso3;
    }

    /**
     * @return string|null
     */
    public function getCodigoTelefonico(): ?string
    {
        return $this->codigoTelefonico;
    }

    /**
     * @param string|null $codigoTelefonico
     */
    public function setCodigoTelefonico(?string $codigoTelefonico): void
    {
        $this->codigoTelefonico = $codigoTelefonico;
    }




}
