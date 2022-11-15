<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_mecanismo_colaboracion")
 */
class InstitucionMecanismoColaboracion extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?string $idMecanismo = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombreMecanismo = null;


    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?string $idTipoMecanismo = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombreTipoMecanismo = null;


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
    public function getIdMecanismo(): ?string
    {
        return $this->idMecanismo;
    }

    /**
     * @param string|null $idMecanismo
     */
    public function setIdMecanismo(?string $idMecanismo): void
    {
        $this->idMecanismo = $idMecanismo;
    }

    /**
     * @return string|null
     */
    public function getNombreMecanismo(): ?string
    {
        return $this->nombreMecanismo;
    }

    /**
     * @param string|null $nombreMecanismo
     */
    public function setNombreMecanismo(?string $nombreMecanismo): void
    {
        $this->nombreMecanismo = $nombreMecanismo;
    }

    /**
     * @return string|null
     */
    public function getIdTipoMecanismo(): ?string
    {
        return $this->idTipoMecanismo;
    }

    /**
     * @param string|null $idTipoMecanismo
     */
    public function setIdTipoMecanismo(?string $idTipoMecanismo): void
    {
        $this->idTipoMecanismo = $idTipoMecanismo;
    }

    /**
     * @return string|null
     */
    public function getNombreTipoMecanismo(): ?string
    {
        return $this->nombreTipoMecanismo;
    }

    /**
     * @param string|null $nombreTipoMecanismo
     */
    public function setNombreTipoMecanismo(?string $nombreTipoMecanismo): void
    {
        $this->nombreTipoMecanismo = $nombreTipoMecanismo;
    }





}
