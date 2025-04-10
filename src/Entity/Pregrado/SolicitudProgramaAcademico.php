<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Postgrado\RamaCiencia;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"nombre", "tipoProgramaAcademico"}, message="Ya existe un programa con el mismo nombre y tipo de programa académico.")
 * @ORM\Table(name="pregrado.tbd_solicitud_programa_academico")
 */
class SolicitudProgramaAcademico
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
     * @Assert\Regex(
     *           pattern= "/^[,0-9a-zA-ZäëïöüáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $nombre = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcion = null;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * @ORM\ManyToOne(targetEntity="EstadoProgramaAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?EstadoProgramaAcademico $estadoProgramaAcademico;


    /**
     * @ORM\ManyToOne(targetEntity="TipoProgramaAcademico")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TipoProgramaAcademico $tipoProgramaAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="TipoOrganismo")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoOrganismo $tipoOrganismo;


    /**
     * @ORM\ManyToOne(targetEntity="OrganismoDemandante")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OrganismoDemandante $organismoDemandante;


    /**
     * @ORM\Column(type="text", nullable=true, length="255")
     */
    private ?string $fundamentacion = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $solicitud = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $resolucion = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $cartaAprobacion = null;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAprobacion;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcionAprobacion = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcionNoAprobacion = null;

    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoDiurno = null;


    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoPorEncuentro = null;
    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoADistancia = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $modalidadDiurno = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $modalidadPorEncuentro = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $modalidadADistancia = false;

    /**
     * @ORM\Column(type="text", nullable=true, length="255")
     */
    private ?string $dictamen = null;


    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $centroRector;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\OrganismoFormador")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?\App\Entity\Pregrado\OrganismoFormador $organismoFormador;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $nombreSolicitante = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $correoSolicitante = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $telefonoSolicitante = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\RamaCiencia")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?RamaCiencia $ramaCiencia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $centroSolicitante;


    /**
     * @ORM\Column(type="string", nullable=true, length="100")
     */
    private ?string $codigoPrograma = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\CategoriaAcreditacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaAcreditacion $categoriaAcreditacion;

    /**
     * @return EstadoProgramaAcademico|null
     */
    public function getEstadoProgramaAcademico(): ?EstadoProgramaAcademico
    {
        return $this->estadoProgramaAcademico;
    }

    /**
     * @param EstadoProgramaAcademico|null $estadoProgramaAcademico
     */
    public function setEstadoProgramaAcademico(?EstadoProgramaAcademico $estadoProgramaAcademico): void
    {
        $this->estadoProgramaAcademico = $estadoProgramaAcademico;
    }

    /**
     * @return TipoProgramaAcademico|null
     */
    public function getTipoProgramaAcademico(): ?TipoProgramaAcademico
    {
        return $this->tipoProgramaAcademico;
    }

    /**
     * @param TipoProgramaAcademico|null $tipoProgramaAcademico
     */
    public function setTipoProgramaAcademico(?TipoProgramaAcademico $tipoProgramaAcademico): void
    {
        $this->tipoProgramaAcademico = $tipoProgramaAcademico;
    }

    /**
     * @return TipoOrganismo|null
     */
    public function getTipoOrganismo(): ?TipoOrganismo
    {
        return $this->tipoOrganismo;
    }

    /**
     * @param TipoOrganismo|null $tipoOrganismo
     */
    public function setTipoOrganismo(?TipoOrganismo $tipoOrganismo): void
    {
        $this->tipoOrganismo = $tipoOrganismo;
    }

    /**
     * @return OrganismoDemandante|null
     */
    public function getOrganismoDemandante(): ?OrganismoDemandante
    {
        return $this->organismoDemandante;
    }

    /**
     * @param OrganismoDemandante|null $organismoDemandante
     */
    public function setOrganismoDemandante(?OrganismoDemandante $organismoDemandante): void
    {
        $this->organismoDemandante = $organismoDemandante;
    }

    /**
     * @return string|null
     */
    public function getFundamentacion(): ?string
    {
        return $this->fundamentacion;
    }

    /**
     * @param string|null $fundamentacion
     */
    public function setFundamentacion(?string $fundamentacion): void
    {
        $this->fundamentacion = $fundamentacion;
    }

    /**
     * @return string|null
     */
    public function getResolucion(): ?string
    {
        return $this->resolucion;
    }

    /**
     * @param string|null $resolucion
     */
    public function setResolucion(?string $resolucion): void
    {
        $this->resolucion = $resolucion;
    }

    /**
     * @return string|null
     */
    public function getCartaAprobacion(): ?string
    {
        return $this->cartaAprobacion;
    }

    /**
     * @param string|null $cartaAprobacion
     */
    public function setCartaAprobacion(?string $cartaAprobacion): void
    {
        $this->cartaAprobacion = $cartaAprobacion;
    }

    /**
     * @return mixed
     */
    public function getFechaAprobacion()
    {
        return $this->fechaAprobacion;
    }

    /**
     * @param mixed $fechaAprobacion
     */
    public function setFechaAprobacion($fechaAprobacion): void
    {
        $this->fechaAprobacion = $fechaAprobacion;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoDiurno(): ?int
    {
        return $this->duracionCursoDiurno;
    }

    /**
     * @param int|null $duracionCursoDiurno
     */
    public function setDuracionCursoDiurno(?int $duracionCursoDiurno): void
    {
        $this->duracionCursoDiurno = $duracionCursoDiurno;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoPorEncuentro(): ?int
    {
        return $this->duracionCursoPorEncuentro;
    }

    /**
     * @param int|null $duracionCursoPorEncuentro
     */
    public function setDuracionCursoPorEncuentro(?int $duracionCursoPorEncuentro): void
    {
        $this->duracionCursoPorEncuentro = $duracionCursoPorEncuentro;
    }


    /**
     * @return string|null
     */
    public function getDictamen(): ?string
    {
        return $this->dictamen;
    }

    /**
     * @param string|null $dictamen
     */
    public function setDictamen(?string $dictamen): void
    {
        $this->dictamen = $dictamen;
    }

    /**
     * @return string|null
     */
    public function getDescripcionAprobacion(): ?string
    {
        return $this->descripcionAprobacion;
    }

    /**
     * @param string|null $descripcionAprobacion
     */
    public function setDescripcionAprobacion(?string $descripcionAprobacion): void
    {
        $this->descripcionAprobacion = $descripcionAprobacion;
    }

    /**
     * @return string|null
     */
    public function getDescripcionNoAprobacion(): ?string
    {
        return $this->descripcionNoAprobacion;
    }

    /**
     * @param string|null $descripcionNoAprobacion
     */
    public function setDescripcionNoAprobacion(?string $descripcionNoAprobacion): void
    {
        $this->descripcionNoAprobacion = $descripcionNoAprobacion;
    }

    /**
     * @return Institucion|null
     */
    public function getCentroRector(): ?Institucion
    {
        return $this->centroRector;
    }

    /**
     * @param Institucion|null $centroRector
     */
    public function setCentroRector(?Institucion $centroRector): void
    {
        $this->centroRector = $centroRector;
    }

    /**
     * @return string|null
     */
    public function getNombreSolicitante(): ?string
    {
        return $this->nombreSolicitante;
    }

    /**
     * @param string|null $nombreSolicitante
     */
    public function setNombreSolicitante(?string $nombreSolicitante): void
    {
        $this->nombreSolicitante = $nombreSolicitante;
    }

    /**
     * @return string|null
     */
    public function getCorreoSolicitante(): ?string
    {
        return $this->correoSolicitante;
    }

    /**
     * @param string|null $correoSolicitante
     */
    public function setCorreoSolicitante(?string $correoSolicitante): void
    {
        $this->correoSolicitante = $correoSolicitante;
    }

    /**
     * @return string|null
     */
    public function getTelefonoSolicitante(): ?string
    {
        return $this->telefonoSolicitante;
    }

    /**
     * @param string|null $telefonoSolicitante
     */
    public function setTelefonoSolicitante(?string $telefonoSolicitante): void
    {
        $this->telefonoSolicitante = $telefonoSolicitante;
    }

    /**
     * @return string|null
     */
    public function getSolicitud(): ?string
    {
        return $this->solicitud;
    }

    /**
     * @param string|null $solicitud
     */
    public function setSolicitud(?string $solicitud): void
    {
        $this->solicitud = $solicitud;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoADistancia()
    {
        return $this->duracionCursoADistancia;
    }

    /**
     * @param int|null $duracionCursoADistancia
     */
    public function setDuracionCursoADistancia($duracionCursoADistancia)
    {
        $this->duracionCursoADistancia = $duracionCursoADistancia;
    }

    /**
     * @return bool|null
     */
    public function getModalidadDiurno()
    {
        return $this->modalidadDiurno;
    }

    /**
     * @param bool|null $modalidadDiurno
     */
    public function setModalidadDiurno($modalidadDiurno)
    {
        $this->modalidadDiurno = $modalidadDiurno;
    }

    /**
     * @return bool|null
     */
    public function getModalidadPorEncuentro()
    {
        return $this->modalidadPorEncuentro;
    }

    /**
     * @param bool|null $modalidadPorEncuentro
     */
    public function setModalidadPorEncuentro($modalidadPorEncuentro)
    {
        $this->modalidadPorEncuentro = $modalidadPorEncuentro;
    }

    /**
     * @return bool|null
     */
    public function getModalidadADistancia()
    {
        return $this->modalidadADistancia;
    }

    /**
     * @param bool|null $modalidadADistancia
     */
    public function setModalidadADistancia($modalidadADistancia)
    {
        $this->modalidadADistancia = $modalidadADistancia;
    }

    /**
     * @return RamaCiencia|null
     */
    public function getRamaCiencia(): ?RamaCiencia
    {
        return $this->ramaCiencia;
    }

    /**
     * @param RamaCiencia|null $ramaCiencia
     */
    public function setRamaCiencia(?RamaCiencia $ramaCiencia): void
    {
        $this->ramaCiencia = $ramaCiencia;
    }

    /**
     * @return Institucion|null
     */
    public function getCentroSolicitante(): ?Institucion
    {
        return $this->centroSolicitante;
    }

    /**
     * @param Institucion|null $centroSolicitante
     */
    public function setCentroSolicitante(?Institucion $centroSolicitante): void
    {
        $this->centroSolicitante = $centroSolicitante;
    }

    /**
     * @return string|null
     */
    public function getCodigoPrograma(): ?string
    {
        return $this->codigoPrograma;
    }

    /**
     * @param string|null $codigoPrograma
     */
    public function setCodigoPrograma($codigoPrograma): void
    {
        $this->codigoPrograma = $codigoPrograma;
    }

    /**
     * @return CategoriaAcreditacion|null
     */
    public function getCategoriaAcreditacion(): ?CategoriaAcreditacion
    {
        return $this->categoriaAcreditacion;
    }

    /**
     * @param CategoriaAcreditacion|null $categoriaAcreditacion
     */
    public function setCategoriaAcreditacion(?CategoriaAcreditacion $categoriaAcreditacion): void
    {
        $this->categoriaAcreditacion = $categoriaAcreditacion;
    }

    /**
     * @return OrganismoFormador|null
     */
    public function getOrganismoFormador(): ?OrganismoFormador
    {
        return $this->organismoFormador;
    }

    /**
     * @param OrganismoFormador|null $organismoFormador
     */
    public function setOrganismoFormador(?OrganismoFormador $organismoFormador)
    {
        $this->organismoFormador = $organismoFormador;
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
    public function setNombre($nombre): void
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
    public function setDescripcion($descripcion): void
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
    public function setActivo($activo): void
    {
        $this->activo = $activo;
    }


}
