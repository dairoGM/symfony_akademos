<?php

namespace App\Entity;

use App\Entity\Security\User;
use App\Repository\InformePersonalizadoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InformePersonalizadoRepository::class)
 */
class InformePersonalizado
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
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
    private $contenido;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $usuario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $privado;

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

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(?string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    /**
     * @param User|null $usuario
     */
    public function setUsuario(?User $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function isPrivado(): ?bool
    {
        return $this->privado;
    }

    public function setPrivado(bool $privado): self
    {
        $this->privado = $privado;

        return $this;
    }


}
