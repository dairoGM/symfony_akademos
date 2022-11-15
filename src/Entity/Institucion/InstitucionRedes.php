<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_redes")
 */
class InstitucionRedes extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $nombreRed = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $descripcionRed = null;

    /**
     * @ORM\ManyToOne(targetEntity="RolRedes")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?RolRedes $rolRedes;


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
     * @return Institucion|null
     */
    public function getRolRedes(): ?RolRedes
    {
        return $this->rolRedes;
    }

    /**
     * @param Institucion|null $rolRedes
     */
    public function setRolRedes(?RolRedes $rolRedes): void
    {
        $this->rolRedes = $rolRedes;
    }

    /**
     * @return string|null
     */
    public function getNombreRed(): ?string
    {
        return $this->nombreRed;
    }

    /**
     * @param string|null $nombreRed
     */
    public function setNombreRed(?string $nombreRed): void
    {
        $this->nombreRed = $nombreRed;
    }

    /**
     * @return string|null
     */
    public function getDescripcionRed(): ?string
    {
        return $this->descripcionRed;
    }

    /**
     * @param string|null $descripcionRed
     */
    public function setDescripcionRed(?string $descripcionRed): void
    {
        $this->descripcionRed = $descripcionRed;
    }







}
