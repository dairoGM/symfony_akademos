<?php

namespace App\Entity;

use App\Repository\NotificacionesUsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Security\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NotificacionesUsuarioRepository::class)
 */
class NotificacionesUsuario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $texto;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $usuarioEnvia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $usuarioRecive;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $leido = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $fechaCreado;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @param mixed $texto
     */
    public function setTexto($texto): void
    {
        $this->texto = $texto;
    }

    /**
     * @return User|null
     */
    public function getUsuarioEnvia(): ?User
    {
        return $this->usuarioEnvia;
    }

    /**
     * @param User|null $usuarioEnvia
     */
    public function setUsuarioEnvia(?User $usuarioEnvia): void
    {
        $this->usuarioEnvia = $usuarioEnvia;
    }

    /**
     * @return User|null
     */
    public function getUsuarioRecive(): ?User
    {
        return $this->usuarioRecive;
    }

    /**
     * @param User|null $usuarioRecive
     */
    public function setUsuarioRecive(?User $usuarioRecive): void
    {
        $this->usuarioRecive = $usuarioRecive;
    }

    /**
     * @return bool|null
     */
    public function getLeido(): ?bool
    {
        return $this->leido;
    }

    /**
     * @param bool|null $leido
     */
    public function setLeido(?bool $leido): void
    {
        $this->leido = $leido;
    }

    /**
     * @return mixed
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * @param mixed $fechaCreado
     */
    public function setFechaCreado($fechaCreado): void
    {
        $this->fechaCreado = $fechaCreado;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }


}
