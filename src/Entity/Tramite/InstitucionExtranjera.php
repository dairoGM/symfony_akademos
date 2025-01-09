<?php

namespace App\Entity\Tramite;

use App\Entity\BaseNomenclator;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\TipoInstitucion;
use App\Entity\Personal\GradoAcademico;
use App\Entity\Estructura\Pais;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbd_institucion_extranjera")
 */
class InstitucionExtranjera extends BaseNomenclator
{

    /**
     * @ORM\Column(type="string", nullable=true, length="100")
     */
    private ?string $provincia = null;

 /**
     * @ORM\Column(type="string", nullable=true, length="100")
     */
    private ?string $siglas = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="20")
     */
    private ?string $codigo = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $rector = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Pais")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Pais $pais ;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $sitioWeb = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="50")
     */
    private ?string $telefono = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="50")
     */
    private ?string $correo = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $direccionSedePrincipal = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $coordenadasSedePrincipal = null;


    public function __construct()
    {

    }

    /**
     * @return string|null
     */
    public function getSiglas(): ?string
    {
        return $this->siglas;
    }

    /**
     * @param string|null $siglas
     */
    public function setSiglas(?string $siglas): void
    {
        $this->siglas = $siglas;
    }

    /**
     * @return string|null
     */
    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    /**
     * @param string|null $codigo
     */
    public function setCodigo(?string $codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string|null
     */
    public function getRector(): ?string
    {
        return $this->rector;
    }

    /**
     * @param string|null $rector
     */
    public function setRector(?string $rector): void
    {
        $this->rector = $rector;
    }

    /**
     * @return Pais|null
     */
    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    /**
     * @param Pais|null $pais
     */
    public function setPais(?Pais $pais): void
    {
        $this->pais = $pais;
    }

    /**
     * @return string|null
     */
    public function getSitioWeb(): ?string
    {
        return $this->sitioWeb;
    }

    /**
     * @param string|null $sitioWeb
     */
    public function setSitioWeb(?string $sitioWeb): void
    {
        $this->sitioWeb = $sitioWeb;
    }

    /**
     * @return string|null
     */
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    /**
     * @param string|null $telefono
     */
    public function setTelefono(?string $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string|null
     */
    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    /**
     * @param string|null $correo
     */
    public function setCorreo(?string $correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return string|null
     */
    public function getDireccionSedePrincipal(): ?string
    {
        return $this->direccionSedePrincipal;
    }

    /**
     * @param string|null $direccionSedePrincipal
     */
    public function setDireccionSedePrincipal(?string $direccionSedePrincipal): void
    {
        $this->direccionSedePrincipal = $direccionSedePrincipal;
    }

    /**
     * @return string|null
     */
    public function getCoordenadasSedePrincipal(): ?string
    {
        return $this->coordenadasSedePrincipal;
    }

    /**
     * @param string|null $coordenadasSedePrincipal
     */
    public function setCoordenadasSedePrincipal(?string $coordenadasSedePrincipal): void
    {
        $this->coordenadasSedePrincipal = $coordenadasSedePrincipal;
    }

    /**
     * @return string|null
     */
    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    /**
     * @param string|null $provincia
     */
    public function setProvincia(?string $provincia): void
    {
        $this->provincia = $provincia;
    }

}
