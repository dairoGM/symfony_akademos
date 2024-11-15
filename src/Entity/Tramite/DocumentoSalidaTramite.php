<?php

namespace App\Entity\Tramite;

use App\Entity\BaseEntity;
use App\Entity\Estructura\Pais;
use App\Entity\Personal\Persona;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Economia\ConceptoGasto;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbr_documento_salida_tramite")
 */
class DocumentoSalidaTramite extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\DocumentoSalida")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?DocumentoSalida $documentoSalida;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tramite\Tramite")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Tramite $tramite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $listo = false;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcion = null;

    /**
     * @return DocumentoSalida|null
     */
    public function getDocumentoSalida(): ?DocumentoSalida
    {
        return $this->documentoSalida;
    }

    /**
     * @param DocumentoSalida|null $documentoSalida
     */
    public function setDocumentoSalida(?DocumentoSalida $documentoSalida): void
    {
        $this->documentoSalida = $documentoSalida;
    }

    /**
     * @return Tramite|null
     */
    public function getTramite(): ?Tramite
    {
        return $this->tramite;
    }

    /**
     * @param Tramite|null $tramite
     */
    public function setTramite(?Tramite $tramite): void
    {
        $this->tramite = $tramite;
    }

    /**
     * @return bool|null
     */
    public function getListo(): ?bool
    {
        return $this->listo;
    }

    /**
     * @param bool|null $listo
     */
    public function setListo(?bool $listo): void
    {
        $this->listo = $listo;
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

}
