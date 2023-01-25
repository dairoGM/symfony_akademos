<?php

namespace App\Entity\Institucion;

use App\Entity\BaseNomenclator;
use App\Entity\Personal\GradoAcademico;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbd_institucion")
 */
class Institucion extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $logo = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="10")
     */
    private ?string $siglas = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="20")
     */
    private ?string $codigo = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $rector = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\GradoAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?GradoAcademico $gradoAcademicoRector = null;

    /**
     * @ORM\ManyToOne(targetEntity="TipoInstitucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoInstitucion $tipoInstitucion;


    /**
     * @ORM\ManyToOne(targetEntity="CategoriaAcreditacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaAcreditacion $categoriaAcreditacion;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $organigrama = null;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $fechaFundacion;


    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $lema = null;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $mision = null;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $vision = null;



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
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $direccionSedePrincipal = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $coordenadasSedePrincipal = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $campusUniversitario = null;

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
    public function getCampusUniversitario(): ?string
    {
        return $this->campusUniversitario;
    }

    /**
     * @param string|null $campusUniversitario
     */
    public function setCampusUniversitario(?string $campusUniversitario): void
    {
        $this->campusUniversitario = $campusUniversitario;
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


    public function __construct()
    {
        $this->fechaFundacion = new \DateTime();
    }


    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    public function getOrganigrama()
    {
        return $this->organigrama;
    }

    public function setOrganigrama($organigrama)
    {
        $this->organigrama = $organigrama;

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


    public function getRector()
    {
        return $this->rector;
    }

    public function setRector($rector)
    {
        $this->rector = $rector;

        return $this;
    }

    /**
     * Get the value of fechaFundacion
     */
    public function getFechaFundacion(): ?\DateTimeInterface
    {
        return $this->fechaFundacion;
    }

    /**
     * Set the value of fechaFundacion
     *
     * @return  self
     */
    public function setFechaFundacion(\DateTimeInterface $fechaFundacion): self
    {
        $this->fechaFundacion = $fechaFundacion;

        return $this;
    }


    /**
     * Get the value of tipoInstitucion
     */
    public function getTipoInstitucion()
    {
        return $this->tipoInstitucion;
    }

    /**
     * Set the value of tipoInstitucion
     *
     * @return  self
     */
    public function setTipoInstitucion($tipoInstitucion)
    {
        $this->tipoInstitucion = $tipoInstitucion;

        return $this;
    }


    public function getLema()
    {
        return $this->lema;
    }

    public function setLema($lema)
    {
        $this->lema = $lema;

        return $this;
    }

    public function getMision()
    {
        return $this->mision;
    }

    public function setMision($mision)
    {
        $this->mision = $mision;

        return $this;
    }


    public function getVision()
    {
        return $this->vision;
    }

    public function setVision($vision)
    {
        $this->vision = $vision;

        return $this;
    }


    /**
     * Get the value of categoriaAcreditacion
     */
    public function getCategoriaAcreditacion()
    {
        return $this->categoriaAcreditacion;
    }

    /**
     * Set the value of categoriaAcreditacion
     *
     * @return  self
     */
    public function setCategoriaAcreditacion($categoriaAcreditacion)
    {
        $this->categoriaAcreditacion = $categoriaAcreditacion;

        return $this;
    }

    public function getGradoAcademicoRector()
    {
        return $this->gradoAcademicoRector;
    }

    public function setGradoAcademicoRector($gradoAcademicoRector)
    {
        $this->gradoAcademicoRector = $gradoAcademicoRector;

        return $this;
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


}
