<?php

namespace App\Entity\Personal;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Responsabilidad;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal.tbd_persona")
 * @UniqueEntity(fields="carnetIdentidad", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @UniqueEntity(fields="numeroSerieCarnetIdentidad", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Persona extends BaseEntity
{
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * @ORM\Column(type="string", length=11, unique=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $carnetIdentidad;
    /**
     * @ORM\Column(type="string", length=11, unique=true, nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $numeroSerieCarnetIdentidad = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $primerNombre;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $segundoNombre = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $primerApellido = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $segundoApellido = null;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private ?string $celular = null;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private ?string $telefono = null;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Provincia")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Provincia $provincia = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Municipio")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Municipio $municipio = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="ClasificacionPersona")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?ClasificacionPersona $clasificacionPersona = null;

    /**
     * @ORM\ManyToOne(targetEntity="NivelEscolar")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?NivelEscolar $nivelEscolar = null;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaDocente")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaDocente $categoriaDocente = null;

    /**
     * @ORM\ManyToOne(targetEntity="Profesion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Profesion $profesion = null;

    /**
     * @ORM\ManyToOne(targetEntity="GradoAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?GradoAcademico $gradoAcademico = null;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaInvestigativa")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaInvestigativa $categoriaInvestigativa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico = null;

    /**
     * @ORM\ManyToOne(targetEntity="Sexo")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Sexo $sexo = null;

    /**
     * @ORM\ManyToOne(targetEntity=CategoriaEstructura::class)
     */
    private $categoriaEstructura = null;

    /**
     * @ORM\ManyToOne(targetEntity=Estructura::class)
     */
    private $estructura = null;

    /**
     * @ORM\ManyToOne(targetEntity=Responsabilidad::class)
     */
    private $responsabilidad;


    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto = null;


    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $twitter = null;


    public function getPrimerNombre(): ?string
    {
        return $this->primerNombre;
    }

    public function setPrimerNombre(string $primerNombre): self
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    public function getSegundoNombre(): ?string
    {
        return $this->segundoNombre;
    }

    public function setSegundoNombre(?string $segundoNombre): self
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    public function getPrimerApellido(): ?string
    {
        return $this->primerApellido;
    }

    public function setPrimerApellido(?string $primerApellido): self
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    public function getSegundoApellido(): ?string
    {
        return $this->segundoApellido;
    }

    public function setSegundoApellido(?string $segundoApellido): self
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    public function getCarnetIdentidad(): ?string
    {
        return $this->carnetIdentidad;
    }

    public function setCarnetIdentidad(string $carnetIdentidad): self
    {
        $this->carnetIdentidad = $carnetIdentidad;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(?string $celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of fechaNacimiento
     */
    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set the value of fechaNacimiento
     *
     * @return  self
     */
    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getClasificacionPersona()
    {
        return $this->clasificacionPersona;
    }

    public function setClasificacionPersona($clasificacionPersona)
    {
        $this->clasificacionPersona = $clasificacionPersona;

        return $this;
    }

    public function getNivelEscolar()
    {
        return $this->nivelEscolar;
    }

    public function setNivelEscolar($nivelEscolar)
    {
        $this->nivelEscolar = $nivelEscolar;

        return $this;
    }

    public function getCategoriaDocente()
    {
        return $this->categoriaDocente;
    }

    public function setCategoriaDocente($categoriaDocente)
    {
        $this->categoriaDocente = $categoriaDocente;

        return $this;
    }

    public function getProfesion()
    {
        return $this->profesion;
    }

    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    public function getGradoAcademico()
    {
        return $this->gradoAcademico;
    }

    public function setGradoAcademico($gradoAcademico)
    {
        $this->gradoAcademico = $gradoAcademico;

        return $this;
    }

    public function getCategoriaInvestigativa()
    {
        return $this->categoriaInvestigativa;
    }

    public function setCategoriaInvestigativa($categoriaInvestigativa)
    {
        $this->categoriaInvestigativa = $categoriaInvestigativa;

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

    public function getMunicipio()
    {
        return $this->municipio;
    }

    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }


    public function getNumeroSerieCarnetIdentidad(): ?string
    {
        return $this->numeroSerieCarnetIdentidad;
    }

    public function setNumeroSerieCarnetIdentidad(string $numeroSerieCarnetIdentidad): self
    {
        $this->numeroSerieCarnetIdentidad = $numeroSerieCarnetIdentidad;

        return $this;
    }

    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getCategoriaEstructura(): ?CategoriaEstructura
    {
        return $this->categoriaEstructura;
    }

    public function setCategoriaEstructura(?CategoriaEstructura $categoriaEstructura): self
    {
        $this->categoriaEstructura = $categoriaEstructura;

        return $this;
    }

    public function getEstructura(): ?Estructura
    {
        return $this->estructura;
    }

    public function setEstructura(?Estructura $estructura): self
    {
        $this->estructura = $estructura;

        return $this;
    }

    public function getResponsabilidad(): ?Responsabilidad
    {
        return $this->responsabilidad;
    }

    public function setResponsabilidad(?Responsabilidad $responsabilidad): self
    {
        $this->responsabilidad = $responsabilidad;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param mixed $foto
     */
    public function setFoto($foto): void
    {
        $this->foto = $foto;
    }


    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * @return SolicitudProgramaAcademico|null
     */
    public function getSolicitudProgramaAcademico(): ?SolicitudProgramaAcademico
    {
        return $this->solicitudProgramaAcademico;
    }

    /**
     * @param SolicitudProgramaAcademico|null $solicitudProgramaAcademico
     */
    public function setSolicitudProgramaAcademico(?SolicitudProgramaAcademico $solicitudProgramaAcademico): void
    {
        $this->solicitudProgramaAcademico = $solicitudProgramaAcademico;
    }


}
