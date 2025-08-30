<?php

namespace App\Entity\RRHH;

use App\Entity\Estructura\Estructura;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rrhh.tbn_grupo")
 */
class Grupo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Estructura\Estructura", mappedBy="grupo")
     */
    private $estructuras;

    /**
     * @return Collection|Estructura[]
     */
    public function getEstructuras(): Collection
    {
        return $this->estructuras;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo = true;

    public function __construct()
    {
        $this->estructuras = new ArrayCollection();
    }

    // Getters y Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }


    public function addEstructura(Estructura $estructura): self
    {
        if (!$this->estructuras->contains($estructura)) {
            $this->estructuras[] = $estructura;
            $estructura->addGrupo($this);
        }

        return $this;
    }

    public function removeEstructura(Estructura $estructura): self
    {
        if ($this->estructuras->contains($estructura)) {
            $this->estructuras->removeElement($estructura);
            $estructura->removeGrupo($this);
        }

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}