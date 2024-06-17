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

}
