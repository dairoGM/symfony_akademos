<?php

namespace App\Entity\Convenio;

use App\Entity\BaseNomenclator;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Estructura\Pais;
use App\Entity\Estructura\Estructura;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Personal\Persona;

/**
 * @ORM\Entity
 * @ORM\Table(name="convenio.tbd_convenio")
 */
class Convenio
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
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $nombre = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcion = null;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio\Modalidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Modalidad $modalidad;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio\Tipo")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Tipo $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Estructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Estructura $institucionCubana;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\InstitucionExtranjera")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?InstitucionExtranjera $institucionExtranjera;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estructura\Pais")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Pais $pais;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaSuscribe;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCaducidad;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cantidadAcciones;

    /**
     * @ORM\ManyToOne(targetEntity=Persona::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $creadoPor;

    /**
     * @ORM\ManyToOne(targetEntity=Persona::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $modificadoPor;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $documento = null;


    public function getInstitucionCubana()
    {
        return $this->institucionCubana;
    }

    /**
     * @param Estructura|null $institucionCubana
     */
    public function setInstitucionCubana(?Estructura $institucionCubana): void
    {
        $this->institucionCubana = $institucionCubana;
    }


    /**
     * @return InstitucionExtranjera|null
     */
    public function getInstitucionExtranjera(): ?InstitucionExtranjera
    {
        return $this->institucionExtranjera;
    }

    /**
     * @param InstitucionExtranjera|null $institucionExtranjera
     */
    public function setInstitucionExtranjera(?InstitucionExtranjera $institucionExtranjera): void
    {
        $this->institucionExtranjera = $institucionExtranjera;
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
     * @return mixed
     */
    public function getFechaSuscribe()
    {
        return $this->fechaSuscribe;
    }

    /**
     * @param mixed $fechaSuscribe
     */
    public function setFechaSuscribe($fechaSuscribe): void
    {
        $this->fechaSuscribe = $fechaSuscribe;
    }

    /**
     * @return mixed
     */
    public function getFechaCaducidad()
    {
        return $this->fechaCaducidad;
    }

    /**
     * @param mixed $fechaCaducidad
     */
    public function setFechaCaducidad($fechaCaducidad): void
    {
        $this->fechaCaducidad = $fechaCaducidad;
    }

    /**
     * @return int|null
     */
    public function getCantidadAcciones(): ?int
    {
        return $this->cantidadAcciones;
    }

    /**
     * @param int|null $cantidadAcciones
     */
    public function setCantidadAcciones(?int $cantidadAcciones): void
    {
        $this->cantidadAcciones = $cantidadAcciones;
    }

    /**
     * @return Modalidad|null
     */
    public function getModalidad(): ?Modalidad
    {
        return $this->modalidad;
    }

    /**
     * @param Modalidad|null $modalidad
     */
    public function setModalidad(?Modalidad $modalidad): void
    {
        $this->modalidad = $modalidad;
    }

    /**
     * @return Tipo|null
     */
    public function getTipo(): ?Tipo
    {
        return $this->tipo;
    }

    /**
     * @param Tipo|null $tipo
     */
    public function setTipo(?Tipo $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * @param mixed $creadoPor
     */
    public function setCreadoPor($creadoPor): void
    {
        $this->creadoPor = $creadoPor;
    }

    /**
     * @return mixed
     */
    public function getModificadoPor()
    {
        return $this->modificadoPor;
    }

    /**
     * @param mixed $modificadoPor
     */
    public function setModificadoPor($modificadoPor): void
    {
        $this->modificadoPor = $modificadoPor;
    }

    /**
     * @return string|null
     */
    public function getDocumento(): ?string
    {
        return $this->documento;
    }

    /**
     * @param string|null $documento
     */
    public function setDocumento(?string $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * @param mixed $creado
     */
    public function setCreado($creado): void
    {
        $this->creado = $creado;
    }

    /**
     * @return mixed
     */
    public function getActualizado()
    {
        return $this->actualizado;
    }

    /**
     * @param mixed $actualizado
     */
    public function setActualizado($actualizado): void
    {
        $this->actualizado = $actualizado;
    }

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string|null $descripcion
     */
    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return bool|null
     */
    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    /**
     * @param bool|null $activo
     */
    public function setActivo(?bool $activo): void
    {
        $this->activo = $activo;
    }


}
