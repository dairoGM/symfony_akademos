<?php

namespace App\Entity\Economia;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="convenio.tbn_obsequio")
 */
class Obsequio extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $marca = null;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $modelo = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $color = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $talla = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $presentacion = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $tipo = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $genero = null;

    /**
     * @return string|null
     */
    public function getMarca(): ?string
    {
        return $this->marca;
    }

    /**
     * @param string|null $marca
     */
    public function setMarca(?string $marca): void
    {
        $this->marca = $marca;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return string|null
     */
    public function getTalla(): ?string
    {
        return $this->talla;
    }

    /**
     * @param string|null $talla
     */
    public function setTalla(?string $talla): void
    {
        $this->talla = $talla;
    }

    /**
     * @return string|null
     */
    public function getPresentacion(): ?string
    {
        return $this->presentacion;
    }

    /**
     * @param string|null $presentacion
     */
    public function setPresentacion(?string $presentacion): void
    {
        $this->presentacion = $presentacion;
    }

    /**
     * @return string|null
     */
    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    /**
     * @param string|null $tipo
     */
    public function setTipo(?string $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string|null
     */
    public function getGenero(): ?string
    {
        return $this->genero;
    }

    /**
     * @param string|null $genero
     */
    public function setGenero(?string $genero): void
    {
        $this->genero = $genero;
    }

    /**
     * @return string|null
     */
    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    /**
     * @param string|null $modelo
     */
    public function setModelo(?string $modelo): void
    {
        $this->modelo = $modelo;
    }


}
