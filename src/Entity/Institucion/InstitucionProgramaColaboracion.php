<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_programa_colaboracion")
 */
class InstitucionProgramaColaboracion extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?string $idPrograma = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombrePrograma = null;


    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?string $idPatrocinador = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombrePatrocinador = null;


    /**
     * @return Institucion|null
     */
    public function getInstitucion(): ?Institucion
    {
        return $this->institucion;
    }

    /**
     * @param Institucion|null $institucion
     */
    public function setInstitucion(?Institucion $institucion): void
    {
        $this->institucion = $institucion;
    }

    /**
     * @return string|null
     */
    public function getIdPrograma(): ?string
    {
        return $this->idPrograma;
    }

    /**
     * @param string|null $idPrograma
     */
    public function setIdPrograma(?string $idPrograma): void
    {
        $this->idPrograma = $idPrograma;
    }

    /**
     * @return string|null
     */
    public function getNombrePrograma(): ?string
    {
        return $this->nombrePrograma;
    }

    /**
     * @param string|null $nombrePrograma
     */
    public function setNombrePrograma(?string $nombrePrograma): void
    {
        $this->nombrePrograma = $nombrePrograma;
    }

    /**
     * @return string|null
     */
    public function getIdPatrocinador(): ?string
    {
        return $this->idPatrocinador;
    }

    /**
     * @param string|null $idPatrocinador
     */
    public function setIdPatrocinador(?string $idPatrocinador): void
    {
        $this->idPatrocinador = $idPatrocinador;
    }

    /**
     * @return string|null
     */
    public function getNombrePatrocinador(): ?string
    {
        return $this->nombrePatrocinador;
    }

    /**
     * @param string|null $nombrePatrocinador
     */
    public function setNombrePatrocinador(?string $nombrePatrocinador): void
    {
        $this->nombrePatrocinador = $nombrePatrocinador;
    }


}
