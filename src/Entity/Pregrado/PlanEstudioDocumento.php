<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Personal\Carrera;
use App\Entity\Postgrado\RamaCiencia;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_plan_estudio_documento")
 */
class PlanEstudioDocumento extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\PlanEstudio")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?PlanEstudio $planEstudio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\Documento")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Documento $documento;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $documentoFisico = null;

    /**
     * @return PlanEstudio|null
     */
    public function getPlanEstudio(): ?PlanEstudio
    {
        return $this->planEstudio;
    }

    /**
     * @param PlanEstudio|null $planEstudio
     */
    public function setPlanEstudio(?PlanEstudio $planEstudio): void
    {
        $this->planEstudio = $planEstudio;
    }

    /**
     * @return Documento|null
     */
    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    /**
     * @param Documento|null $documento
     */
    public function setDocumento(?Documento $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return string|null
     */
    public function getDocumentoFisico(): ?string
    {
        return $this->documentoFisico;
    }

    /**
     * @param string|null $documentoFisico
     */
    public function setDocumentoFisico(?string $documentoFisico): void
    {
        $this->documentoFisico = $documentoFisico;
    }


}
