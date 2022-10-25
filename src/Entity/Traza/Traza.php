<?php

namespace App\Entity\Traza;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Personal\Persona;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="traza.tbd_traza")
 */
class Traza
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $creado;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $actualizado;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $navegador;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sistemaOperativo;

    /**
     * @ORM\Column(type="text")
     */
    private $data;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $dataAnterior;

    /**
     * @ORM\ManyToOne(targetEntity="TipoTraza")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TipoTraza $tipoTraza;


    /**
     * @ORM\ManyToOne(targetEntity="Accion")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Accion $accion;


    /**
     * @ORM\ManyToOne(targetEntity="Objeto")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Objeto $objeto;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\Persona")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Persona $persona;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreado()
    {
        return $this->creado;
    }

    public function getActualizado()
    {
        return $this->actualizado;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getNavegador(): ?string
    {
        return $this->navegador;
    }

    public function setNavegador(string $navegador): self
    {
        $this->navegador = $navegador;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }


    public function getTipoTraza()
    {
        return $this->tipoTraza;
    }

    public function setTipoTraza($tipoTraza)
    {
        $this->tipoTraza = $tipoTraza;

        return $this;
    }

    public function getAccion()
    {
        return $this->accion;
    }

    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    public function getObjeto()
    {
        return $this->objeto;
    }

    public function setObjeto($objeto)
    {
        $this->objeto = $objeto;

        return $this;
    }


    public function getPersona()
    {
        return $this->persona;
    }

    public function setPersona($persona)
    {
        $this->persona = $persona;

        return $this;
    }

    public function getSistemaOperativo(): ?string
    {
        return $this->sistemaOperativo;
    }

    public function setSistemaOperativo(string $sistemaOperativo): self
    {
        $this->sistemaOperativo = $sistemaOperativo;

        return $this;
    }

    public function getDataAnterior(): ?string
    {
        return $this->dataAnterior;
    }

    public function setDataAnterior(string $dataAnterior): self
    {
        $this->dataAnterior = $dataAnterior;

        return $this;
    }
}
