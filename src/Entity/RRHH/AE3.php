<?php

namespace App\Entity\RRHH;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Estructura\Estructura;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="rrhh.tbd_ae3", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_mes_anio_ae3", columns={"mes", "anno", "entidad_id"})})
 * @UniqueEntity(fields={"mes", "anno", "entidad"}, message="Ya existe un registro para este mes y año.")
 */
class AE3
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Estructura::class)
     */
    private $entidad = null;

    /**
     * @ORM\Column(type="integer")
     */
    private $mes;

    /**
     * @ORM\Column(type="integer")
     */
    private $anno;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $documento;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_cuadros_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_docentes_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_administrativos_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuadros_investigacion_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_cuadros_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_tecnicos_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tiempo_completo_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asesores_metodologos_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $investigadores_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otros_tecnicos_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $administrativos_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servicio_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operarios_dr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_dr;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_total_cubierta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_de_ellos_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_jovenes_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_jovenes_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_fem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_pa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_as;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_i;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_it;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_ia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_iag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_ai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_aux_tec_doc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_msc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profesores_tipo_parcial_dr;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * @param null $entidad
     */
    public function setEntidad($entidad): void
    {
        $this->entidad = $entidad;
    }


    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getAnno()
    {
        return $this->anno;
    }

    /**
     * @param mixed $anno
     */
    public function setAnno($anno): void
    {
        $this->anno = $anno;
    }

    /**
     * @return mixed
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param mixed $documento
     */
    public function setDocumento($documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosTotalCubierta()
    {
        return $this->total_cuadros_total_cubierta;
    }

    /**
     * @param mixed $total_cuadros_total_cubierta
     */
    public function setTotalCuadrosTotalCubierta($total_cuadros_total_cubierta): void
    {
        $this->total_cuadros_total_cubierta = $total_cuadros_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosDeEllosFem()
    {
        return $this->total_cuadros_de_ellos_fem;
    }

    /**
     * @param mixed $total_cuadros_de_ellos_fem
     */
    public function setTotalCuadrosDeEllosFem($total_cuadros_de_ellos_fem): void
    {
        $this->total_cuadros_de_ellos_fem = $total_cuadros_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosJovenesTotal()
    {
        return $this->total_cuadros_jovenes_total;
    }

    /**
     * @param mixed $total_cuadros_jovenes_total
     */
    public function setTotalCuadrosJovenesTotal($total_cuadros_jovenes_total): void
    {
        $this->total_cuadros_jovenes_total = $total_cuadros_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosJovenesFem()
    {
        return $this->total_cuadros_jovenes_fem;
    }

    /**
     * @param mixed $total_cuadros_jovenes_fem
     */
    public function setTotalCuadrosJovenesFem($total_cuadros_jovenes_fem): void
    {
        $this->total_cuadros_jovenes_fem = $total_cuadros_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosFem()
    {
        return $this->total_cuadros_fem;
    }

    /**
     * @param mixed $total_cuadros_fem
     */
    public function setTotalCuadrosFem($total_cuadros_fem): void
    {
        $this->total_cuadros_fem = $total_cuadros_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosPt()
    {
        return $this->total_cuadros_pt;
    }

    /**
     * @param mixed $total_cuadros_pt
     */
    public function setTotalCuadrosPt($total_cuadros_pt): void
    {
        $this->total_cuadros_pt = $total_cuadros_pt;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosPa()
    {
        return $this->total_cuadros_pa;
    }

    /**
     * @param mixed $total_cuadros_pa
     */
    public function setTotalCuadrosPa($total_cuadros_pa): void
    {
        $this->total_cuadros_pa = $total_cuadros_pa;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosAs()
    {
        return $this->total_cuadros_as;
    }

    /**
     * @param mixed $total_cuadros_as
     */
    public function setTotalCuadrosAs($total_cuadros_as): void
    {
        $this->total_cuadros_as = $total_cuadros_as;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosI()
    {
        return $this->total_cuadros_i;
    }

    /**
     * @param mixed $total_cuadros_i
     */
    public function setTotalCuadrosI($total_cuadros_i): void
    {
        $this->total_cuadros_i = $total_cuadros_i;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosIt()
    {
        return $this->total_cuadros_it;
    }

    /**
     * @param mixed $total_cuadros_it
     */
    public function setTotalCuadrosIt($total_cuadros_it): void
    {
        $this->total_cuadros_it = $total_cuadros_it;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosIa()
    {
        return $this->total_cuadros_ia;
    }

    /**
     * @param mixed $total_cuadros_ia
     */
    public function setTotalCuadrosIa($total_cuadros_ia): void
    {
        $this->total_cuadros_ia = $total_cuadros_ia;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosIag()
    {
        return $this->total_cuadros_iag;
    }

    /**
     * @param mixed $total_cuadros_iag
     */
    public function setTotalCuadrosIag($total_cuadros_iag): void
    {
        $this->total_cuadros_iag = $total_cuadros_iag;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosAi()
    {
        return $this->total_cuadros_ai;
    }

    /**
     * @param mixed $total_cuadros_ai
     */
    public function setTotalCuadrosAi($total_cuadros_ai): void
    {
        $this->total_cuadros_ai = $total_cuadros_ai;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosAuxTecDoc()
    {
        return $this->total_cuadros_aux_tec_doc;
    }

    /**
     * @param mixed $total_cuadros_aux_tec_doc
     */
    public function setTotalCuadrosAuxTecDoc($total_cuadros_aux_tec_doc): void
    {
        $this->total_cuadros_aux_tec_doc = $total_cuadros_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosMsc()
    {
        return $this->total_cuadros_msc;
    }

    /**
     * @param mixed $total_cuadros_msc
     */
    public function setTotalCuadrosMsc($total_cuadros_msc): void
    {
        $this->total_cuadros_msc = $total_cuadros_msc;
    }

    /**
     * @return mixed
     */
    public function getTotalCuadrosDr()
    {
        return $this->total_cuadros_dr;
    }

    /**
     * @param mixed $total_cuadros_dr
     */
    public function setTotalCuadrosDr($total_cuadros_dr): void
    {
        $this->total_cuadros_dr = $total_cuadros_dr;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesTotalCubierta()
    {
        return $this->cuadros_docentes_total_cubierta;
    }

    /**
     * @param mixed $cuadros_docentes_total_cubierta
     */
    public function setCuadrosDocentesTotalCubierta($cuadros_docentes_total_cubierta): void
    {
        $this->cuadros_docentes_total_cubierta = $cuadros_docentes_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesDeEllosFem()
    {
        return $this->cuadros_docentes_de_ellos_fem;
    }

    /**
     * @param mixed $cuadros_docentes_de_ellos_fem
     */
    public function setCuadrosDocentesDeEllosFem($cuadros_docentes_de_ellos_fem): void
    {
        $this->cuadros_docentes_de_ellos_fem = $cuadros_docentes_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesJovenesTotal()
    {
        return $this->cuadros_docentes_jovenes_total;
    }

    /**
     * @param mixed $cuadros_docentes_jovenes_total
     */
    public function setCuadrosDocentesJovenesTotal($cuadros_docentes_jovenes_total): void
    {
        $this->cuadros_docentes_jovenes_total = $cuadros_docentes_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesJovenesFem()
    {
        return $this->cuadros_docentes_jovenes_fem;
    }

    /**
     * @param mixed $cuadros_docentes_jovenes_fem
     */
    public function setCuadrosDocentesJovenesFem($cuadros_docentes_jovenes_fem): void
    {
        $this->cuadros_docentes_jovenes_fem = $cuadros_docentes_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesFem()
    {
        return $this->cuadros_docentes_fem;
    }

    /**
     * @param mixed $cuadros_docentes_fem
     */
    public function setCuadrosDocentesFem($cuadros_docentes_fem): void
    {
        $this->cuadros_docentes_fem = $cuadros_docentes_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesPt()
    {
        return $this->cuadros_docentes_pt;
    }

    /**
     * @param mixed $cuadros_docentes_pt
     */
    public function setCuadrosDocentesPt($cuadros_docentes_pt): void
    {
        $this->cuadros_docentes_pt = $cuadros_docentes_pt;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesPa()
    {
        return $this->cuadros_docentes_pa;
    }

    /**
     * @param mixed $cuadros_docentes_pa
     */
    public function setCuadrosDocentesPa($cuadros_docentes_pa): void
    {
        $this->cuadros_docentes_pa = $cuadros_docentes_pa;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesAs()
    {
        return $this->cuadros_docentes_as;
    }

    /**
     * @param mixed $cuadros_docentes_as
     */
    public function setCuadrosDocentesAs($cuadros_docentes_as): void
    {
        $this->cuadros_docentes_as = $cuadros_docentes_as;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesI()
    {
        return $this->cuadros_docentes_i;
    }

    /**
     * @param mixed $cuadros_docentes_i
     */
    public function setCuadrosDocentesI($cuadros_docentes_i): void
    {
        $this->cuadros_docentes_i = $cuadros_docentes_i;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesIt()
    {
        return $this->cuadros_docentes_it;
    }

    /**
     * @param mixed $cuadros_docentes_it
     */
    public function setCuadrosDocentesIt($cuadros_docentes_it): void
    {
        $this->cuadros_docentes_it = $cuadros_docentes_it;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesIa()
    {
        return $this->cuadros_docentes_ia;
    }

    /**
     * @param mixed $cuadros_docentes_ia
     */
    public function setCuadrosDocentesIa($cuadros_docentes_ia): void
    {
        $this->cuadros_docentes_ia = $cuadros_docentes_ia;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesIag()
    {
        return $this->cuadros_docentes_iag;
    }

    /**
     * @param mixed $cuadros_docentes_iag
     */
    public function setCuadrosDocentesIag($cuadros_docentes_iag): void
    {
        $this->cuadros_docentes_iag = $cuadros_docentes_iag;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesAi()
    {
        return $this->cuadros_docentes_ai;
    }

    /**
     * @param mixed $cuadros_docentes_ai
     */
    public function setCuadrosDocentesAi($cuadros_docentes_ai): void
    {
        $this->cuadros_docentes_ai = $cuadros_docentes_ai;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesAuxTecDoc()
    {
        return $this->cuadros_docentes_aux_tec_doc;
    }

    /**
     * @param mixed $cuadros_docentes_aux_tec_doc
     */
    public function setCuadrosDocentesAuxTecDoc($cuadros_docentes_aux_tec_doc): void
    {
        $this->cuadros_docentes_aux_tec_doc = $cuadros_docentes_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesMsc()
    {
        return $this->cuadros_docentes_msc;
    }

    /**
     * @param mixed $cuadros_docentes_msc
     */
    public function setCuadrosDocentesMsc($cuadros_docentes_msc): void
    {
        $this->cuadros_docentes_msc = $cuadros_docentes_msc;
    }

    /**
     * @return mixed
     */
    public function getCuadrosDocentesDr()
    {
        return $this->cuadros_docentes_dr;
    }

    /**
     * @param mixed $cuadros_docentes_dr
     */
    public function setCuadrosDocentesDr($cuadros_docentes_dr): void
    {
        $this->cuadros_docentes_dr = $cuadros_docentes_dr;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosTotalCubierta()
    {
        return $this->cuadros_administrativos_total_cubierta;
    }

    /**
     * @param mixed $cuadros_administrativos_total_cubierta
     */
    public function setCuadrosAdministrativosTotalCubierta($cuadros_administrativos_total_cubierta): void
    {
        $this->cuadros_administrativos_total_cubierta = $cuadros_administrativos_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosDeEllosFem()
    {
        return $this->cuadros_administrativos_de_ellos_fem;
    }

    /**
     * @param mixed $cuadros_administrativos_de_ellos_fem
     */
    public function setCuadrosAdministrativosDeEllosFem($cuadros_administrativos_de_ellos_fem): void
    {
        $this->cuadros_administrativos_de_ellos_fem = $cuadros_administrativos_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosJovenesTotal()
    {
        return $this->cuadros_administrativos_jovenes_total;
    }

    /**
     * @param mixed $cuadros_administrativos_jovenes_total
     */
    public function setCuadrosAdministrativosJovenesTotal($cuadros_administrativos_jovenes_total): void
    {
        $this->cuadros_administrativos_jovenes_total = $cuadros_administrativos_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosJovenesFem()
    {
        return $this->cuadros_administrativos_jovenes_fem;
    }

    /**
     * @param mixed $cuadros_administrativos_jovenes_fem
     */
    public function setCuadrosAdministrativosJovenesFem($cuadros_administrativos_jovenes_fem): void
    {
        $this->cuadros_administrativos_jovenes_fem = $cuadros_administrativos_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosFem()
    {
        return $this->cuadros_administrativos_fem;
    }

    /**
     * @param mixed $cuadros_administrativos_fem
     */
    public function setCuadrosAdministrativosFem($cuadros_administrativos_fem): void
    {
        $this->cuadros_administrativos_fem = $cuadros_administrativos_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosPt()
    {
        return $this->cuadros_administrativos_pt;
    }

    /**
     * @param mixed $cuadros_administrativos_pt
     */
    public function setCuadrosAdministrativosPt($cuadros_administrativos_pt): void
    {
        $this->cuadros_administrativos_pt = $cuadros_administrativos_pt;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosPa()
    {
        return $this->cuadros_administrativos_pa;
    }

    /**
     * @param mixed $cuadros_administrativos_pa
     */
    public function setCuadrosAdministrativosPa($cuadros_administrativos_pa): void
    {
        $this->cuadros_administrativos_pa = $cuadros_administrativos_pa;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosAs()
    {
        return $this->cuadros_administrativos_as;
    }

    /**
     * @param mixed $cuadros_administrativos_as
     */
    public function setCuadrosAdministrativosAs($cuadros_administrativos_as): void
    {
        $this->cuadros_administrativos_as = $cuadros_administrativos_as;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosI()
    {
        return $this->cuadros_administrativos_i;
    }

    /**
     * @param mixed $cuadros_administrativos_i
     */
    public function setCuadrosAdministrativosI($cuadros_administrativos_i): void
    {
        $this->cuadros_administrativos_i = $cuadros_administrativos_i;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosIt()
    {
        return $this->cuadros_administrativos_it;
    }

    /**
     * @param mixed $cuadros_administrativos_it
     */
    public function setCuadrosAdministrativosIt($cuadros_administrativos_it): void
    {
        $this->cuadros_administrativos_it = $cuadros_administrativos_it;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosIa()
    {
        return $this->cuadros_administrativos_ia;
    }

    /**
     * @param mixed $cuadros_administrativos_ia
     */
    public function setCuadrosAdministrativosIa($cuadros_administrativos_ia): void
    {
        $this->cuadros_administrativos_ia = $cuadros_administrativos_ia;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosIag()
    {
        return $this->cuadros_administrativos_iag;
    }

    /**
     * @param mixed $cuadros_administrativos_iag
     */
    public function setCuadrosAdministrativosIag($cuadros_administrativos_iag): void
    {
        $this->cuadros_administrativos_iag = $cuadros_administrativos_iag;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosAi()
    {
        return $this->cuadros_administrativos_ai;
    }

    /**
     * @param mixed $cuadros_administrativos_ai
     */
    public function setCuadrosAdministrativosAi($cuadros_administrativos_ai): void
    {
        $this->cuadros_administrativos_ai = $cuadros_administrativos_ai;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosAuxTecDoc()
    {
        return $this->cuadros_administrativos_aux_tec_doc;
    }

    /**
     * @param mixed $cuadros_administrativos_aux_tec_doc
     */
    public function setCuadrosAdministrativosAuxTecDoc($cuadros_administrativos_aux_tec_doc): void
    {
        $this->cuadros_administrativos_aux_tec_doc = $cuadros_administrativos_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosMsc()
    {
        return $this->cuadros_administrativos_msc;
    }

    /**
     * @param mixed $cuadros_administrativos_msc
     */
    public function setCuadrosAdministrativosMsc($cuadros_administrativos_msc): void
    {
        $this->cuadros_administrativos_msc = $cuadros_administrativos_msc;
    }

    /**
     * @return mixed
     */
    public function getCuadrosAdministrativosDr()
    {
        return $this->cuadros_administrativos_dr;
    }

    /**
     * @param mixed $cuadros_administrativos_dr
     */
    public function setCuadrosAdministrativosDr($cuadros_administrativos_dr): void
    {
        $this->cuadros_administrativos_dr = $cuadros_administrativos_dr;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionTotalCubierta()
    {
        return $this->cuadros_investigacion_total_cubierta;
    }

    /**
     * @param mixed $cuadros_investigacion_total_cubierta
     */
    public function setCuadrosInvestigacionTotalCubierta($cuadros_investigacion_total_cubierta): void
    {
        $this->cuadros_investigacion_total_cubierta = $cuadros_investigacion_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionDeEllosFem()
    {
        return $this->cuadros_investigacion_de_ellos_fem;
    }

    /**
     * @param mixed $cuadros_investigacion_de_ellos_fem
     */
    public function setCuadrosInvestigacionDeEllosFem($cuadros_investigacion_de_ellos_fem): void
    {
        $this->cuadros_investigacion_de_ellos_fem = $cuadros_investigacion_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionJovenesTotal()
    {
        return $this->cuadros_investigacion_jovenes_total;
    }

    /**
     * @param mixed $cuadros_investigacion_jovenes_total
     */
    public function setCuadrosInvestigacionJovenesTotal($cuadros_investigacion_jovenes_total): void
    {
        $this->cuadros_investigacion_jovenes_total = $cuadros_investigacion_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionJovenesFem()
    {
        return $this->cuadros_investigacion_jovenes_fem;
    }

    /**
     * @param mixed $cuadros_investigacion_jovenes_fem
     */
    public function setCuadrosInvestigacionJovenesFem($cuadros_investigacion_jovenes_fem): void
    {
        $this->cuadros_investigacion_jovenes_fem = $cuadros_investigacion_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionFem()
    {
        return $this->cuadros_investigacion_fem;
    }

    /**
     * @param mixed $cuadros_investigacion_fem
     */
    public function setCuadrosInvestigacionFem($cuadros_investigacion_fem): void
    {
        $this->cuadros_investigacion_fem = $cuadros_investigacion_fem;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionPt()
    {
        return $this->cuadros_investigacion_pt;
    }

    /**
     * @param mixed $cuadros_investigacion_pt
     */
    public function setCuadrosInvestigacionPt($cuadros_investigacion_pt): void
    {
        $this->cuadros_investigacion_pt = $cuadros_investigacion_pt;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionPa()
    {
        return $this->cuadros_investigacion_pa;
    }

    /**
     * @param mixed $cuadros_investigacion_pa
     */
    public function setCuadrosInvestigacionPa($cuadros_investigacion_pa): void
    {
        $this->cuadros_investigacion_pa = $cuadros_investigacion_pa;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionAs()
    {
        return $this->cuadros_investigacion_as;
    }

    /**
     * @param mixed $cuadros_investigacion_as
     */
    public function setCuadrosInvestigacionAs($cuadros_investigacion_as): void
    {
        $this->cuadros_investigacion_as = $cuadros_investigacion_as;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionI()
    {
        return $this->cuadros_investigacion_i;
    }

    /**
     * @param mixed $cuadros_investigacion_i
     */
    public function setCuadrosInvestigacionI($cuadros_investigacion_i): void
    {
        $this->cuadros_investigacion_i = $cuadros_investigacion_i;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionIt()
    {
        return $this->cuadros_investigacion_it;
    }

    /**
     * @param mixed $cuadros_investigacion_it
     */
    public function setCuadrosInvestigacionIt($cuadros_investigacion_it): void
    {
        $this->cuadros_investigacion_it = $cuadros_investigacion_it;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionIa()
    {
        return $this->cuadros_investigacion_ia;
    }

    /**
     * @param mixed $cuadros_investigacion_ia
     */
    public function setCuadrosInvestigacionIa($cuadros_investigacion_ia): void
    {
        $this->cuadros_investigacion_ia = $cuadros_investigacion_ia;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionIag()
    {
        return $this->cuadros_investigacion_iag;
    }

    /**
     * @param mixed $cuadros_investigacion_iag
     */
    public function setCuadrosInvestigacionIag($cuadros_investigacion_iag): void
    {
        $this->cuadros_investigacion_iag = $cuadros_investigacion_iag;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionAi()
    {
        return $this->cuadros_investigacion_ai;
    }

    /**
     * @param mixed $cuadros_investigacion_ai
     */
    public function setCuadrosInvestigacionAi($cuadros_investigacion_ai): void
    {
        $this->cuadros_investigacion_ai = $cuadros_investigacion_ai;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionAuxTecDoc()
    {
        return $this->cuadros_investigacion_aux_tec_doc;
    }

    /**
     * @param mixed $cuadros_investigacion_aux_tec_doc
     */
    public function setCuadrosInvestigacionAuxTecDoc($cuadros_investigacion_aux_tec_doc): void
    {
        $this->cuadros_investigacion_aux_tec_doc = $cuadros_investigacion_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionMsc()
    {
        return $this->cuadros_investigacion_msc;
    }

    /**
     * @param mixed $cuadros_investigacion_msc
     */
    public function setCuadrosInvestigacionMsc($cuadros_investigacion_msc): void
    {
        $this->cuadros_investigacion_msc = $cuadros_investigacion_msc;
    }

    /**
     * @return mixed
     */
    public function getCuadrosInvestigacionDr()
    {
        return $this->cuadros_investigacion_dr;
    }

    /**
     * @param mixed $cuadros_investigacion_dr
     */
    public function setCuadrosInvestigacionDr($cuadros_investigacion_dr): void
    {
        $this->cuadros_investigacion_dr = $cuadros_investigacion_dr;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosTotalCubierta()
    {
        return $this->otros_cuadros_total_cubierta;
    }

    /**
     * @param mixed $otros_cuadros_total_cubierta
     */
    public function setOtrosCuadrosTotalCubierta($otros_cuadros_total_cubierta): void
    {
        $this->otros_cuadros_total_cubierta = $otros_cuadros_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosDeEllosFem()
    {
        return $this->otros_cuadros_de_ellos_fem;
    }

    /**
     * @param mixed $otros_cuadros_de_ellos_fem
     */
    public function setOtrosCuadrosDeEllosFem($otros_cuadros_de_ellos_fem): void
    {
        $this->otros_cuadros_de_ellos_fem = $otros_cuadros_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosJovenesTotal()
    {
        return $this->otros_cuadros_jovenes_total;
    }

    /**
     * @param mixed $otros_cuadros_jovenes_total
     */
    public function setOtrosCuadrosJovenesTotal($otros_cuadros_jovenes_total): void
    {
        $this->otros_cuadros_jovenes_total = $otros_cuadros_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosJovenesFem()
    {
        return $this->otros_cuadros_jovenes_fem;
    }

    /**
     * @param mixed $otros_cuadros_jovenes_fem
     */
    public function setOtrosCuadrosJovenesFem($otros_cuadros_jovenes_fem): void
    {
        $this->otros_cuadros_jovenes_fem = $otros_cuadros_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosFem()
    {
        return $this->otros_cuadros_fem;
    }

    /**
     * @param mixed $otros_cuadros_fem
     */
    public function setOtrosCuadrosFem($otros_cuadros_fem): void
    {
        $this->otros_cuadros_fem = $otros_cuadros_fem;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosPt()
    {
        return $this->otros_cuadros_pt;
    }

    /**
     * @param mixed $otros_cuadros_pt
     */
    public function setOtrosCuadrosPt($otros_cuadros_pt): void
    {
        $this->otros_cuadros_pt = $otros_cuadros_pt;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosPa()
    {
        return $this->otros_cuadros_pa;
    }

    /**
     * @param mixed $otros_cuadros_pa
     */
    public function setOtrosCuadrosPa($otros_cuadros_pa): void
    {
        $this->otros_cuadros_pa = $otros_cuadros_pa;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosAs()
    {
        return $this->otros_cuadros_as;
    }

    /**
     * @param mixed $otros_cuadros_as
     */
    public function setOtrosCuadrosAs($otros_cuadros_as): void
    {
        $this->otros_cuadros_as = $otros_cuadros_as;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosI()
    {
        return $this->otros_cuadros_i;
    }

    /**
     * @param mixed $otros_cuadros_i
     */
    public function setOtrosCuadrosI($otros_cuadros_i): void
    {
        $this->otros_cuadros_i = $otros_cuadros_i;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosIt()
    {
        return $this->otros_cuadros_it;
    }

    /**
     * @param mixed $otros_cuadros_it
     */
    public function setOtrosCuadrosIt($otros_cuadros_it): void
    {
        $this->otros_cuadros_it = $otros_cuadros_it;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosIa()
    {
        return $this->otros_cuadros_ia;
    }

    /**
     * @param mixed $otros_cuadros_ia
     */
    public function setOtrosCuadrosIa($otros_cuadros_ia): void
    {
        $this->otros_cuadros_ia = $otros_cuadros_ia;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosIag()
    {
        return $this->otros_cuadros_iag;
    }

    /**
     * @param mixed $otros_cuadros_iag
     */
    public function setOtrosCuadrosIag($otros_cuadros_iag): void
    {
        $this->otros_cuadros_iag = $otros_cuadros_iag;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosAi()
    {
        return $this->otros_cuadros_ai;
    }

    /**
     * @param mixed $otros_cuadros_ai
     */
    public function setOtrosCuadrosAi($otros_cuadros_ai): void
    {
        $this->otros_cuadros_ai = $otros_cuadros_ai;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosAuxTecDoc()
    {
        return $this->otros_cuadros_aux_tec_doc;
    }

    /**
     * @param mixed $otros_cuadros_aux_tec_doc
     */
    public function setOtrosCuadrosAuxTecDoc($otros_cuadros_aux_tec_doc): void
    {
        $this->otros_cuadros_aux_tec_doc = $otros_cuadros_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosMsc()
    {
        return $this->otros_cuadros_msc;
    }

    /**
     * @param mixed $otros_cuadros_msc
     */
    public function setOtrosCuadrosMsc($otros_cuadros_msc): void
    {
        $this->otros_cuadros_msc = $otros_cuadros_msc;
    }

    /**
     * @return mixed
     */
    public function getOtrosCuadrosDr()
    {
        return $this->otros_cuadros_dr;
    }

    /**
     * @param mixed $otros_cuadros_dr
     */
    public function setOtrosCuadrosDr($otros_cuadros_dr): void
    {
        $this->otros_cuadros_dr = $otros_cuadros_dr;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosTotalCubierta()
    {
        return $this->total_tecnicos_total_cubierta;
    }

    /**
     * @param mixed $total_tecnicos_total_cubierta
     */
    public function setTotalTecnicosTotalCubierta($total_tecnicos_total_cubierta): void
    {
        $this->total_tecnicos_total_cubierta = $total_tecnicos_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosDeEllosFem()
    {
        return $this->total_tecnicos_de_ellos_fem;
    }

    /**
     * @param mixed $total_tecnicos_de_ellos_fem
     */
    public function setTotalTecnicosDeEllosFem($total_tecnicos_de_ellos_fem): void
    {
        $this->total_tecnicos_de_ellos_fem = $total_tecnicos_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosJovenesTotal()
    {
        return $this->total_tecnicos_jovenes_total;
    }

    /**
     * @param mixed $total_tecnicos_jovenes_total
     */
    public function setTotalTecnicosJovenesTotal($total_tecnicos_jovenes_total): void
    {
        $this->total_tecnicos_jovenes_total = $total_tecnicos_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosJovenesFem()
    {
        return $this->total_tecnicos_jovenes_fem;
    }

    /**
     * @param mixed $total_tecnicos_jovenes_fem
     */
    public function setTotalTecnicosJovenesFem($total_tecnicos_jovenes_fem): void
    {
        $this->total_tecnicos_jovenes_fem = $total_tecnicos_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosFem()
    {
        return $this->total_tecnicos_fem;
    }

    /**
     * @param mixed $total_tecnicos_fem
     */
    public function setTotalTecnicosFem($total_tecnicos_fem): void
    {
        $this->total_tecnicos_fem = $total_tecnicos_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosPt()
    {
        return $this->total_tecnicos_pt;
    }

    /**
     * @param mixed $total_tecnicos_pt
     */
    public function setTotalTecnicosPt($total_tecnicos_pt): void
    {
        $this->total_tecnicos_pt = $total_tecnicos_pt;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosPa()
    {
        return $this->total_tecnicos_pa;
    }

    /**
     * @param mixed $total_tecnicos_pa
     */
    public function setTotalTecnicosPa($total_tecnicos_pa): void
    {
        $this->total_tecnicos_pa = $total_tecnicos_pa;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosAs()
    {
        return $this->total_tecnicos_as;
    }

    /**
     * @param mixed $total_tecnicos_as
     */
    public function setTotalTecnicosAs($total_tecnicos_as): void
    {
        $this->total_tecnicos_as = $total_tecnicos_as;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosI()
    {
        return $this->total_tecnicos_i;
    }

    /**
     * @param mixed $total_tecnicos_i
     */
    public function setTotalTecnicosI($total_tecnicos_i): void
    {
        $this->total_tecnicos_i = $total_tecnicos_i;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosIt()
    {
        return $this->total_tecnicos_it;
    }

    /**
     * @param mixed $total_tecnicos_it
     */
    public function setTotalTecnicosIt($total_tecnicos_it): void
    {
        $this->total_tecnicos_it = $total_tecnicos_it;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosIa()
    {
        return $this->total_tecnicos_ia;
    }

    /**
     * @param mixed $total_tecnicos_ia
     */
    public function setTotalTecnicosIa($total_tecnicos_ia): void
    {
        $this->total_tecnicos_ia = $total_tecnicos_ia;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosIag()
    {
        return $this->total_tecnicos_iag;
    }

    /**
     * @param mixed $total_tecnicos_iag
     */
    public function setTotalTecnicosIag($total_tecnicos_iag): void
    {
        $this->total_tecnicos_iag = $total_tecnicos_iag;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosAi()
    {
        return $this->total_tecnicos_ai;
    }

    /**
     * @param mixed $total_tecnicos_ai
     */
    public function setTotalTecnicosAi($total_tecnicos_ai): void
    {
        $this->total_tecnicos_ai = $total_tecnicos_ai;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosAuxTecDoc()
    {
        return $this->total_tecnicos_aux_tec_doc;
    }

    /**
     * @param mixed $total_tecnicos_aux_tec_doc
     */
    public function setTotalTecnicosAuxTecDoc($total_tecnicos_aux_tec_doc): void
    {
        $this->total_tecnicos_aux_tec_doc = $total_tecnicos_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosMsc()
    {
        return $this->total_tecnicos_msc;
    }

    /**
     * @param mixed $total_tecnicos_msc
     */
    public function setTotalTecnicosMsc($total_tecnicos_msc): void
    {
        $this->total_tecnicos_msc = $total_tecnicos_msc;
    }

    /**
     * @return mixed
     */
    public function getTotalTecnicosDr()
    {
        return $this->total_tecnicos_dr;
    }

    /**
     * @param mixed $total_tecnicos_dr
     */
    public function setTotalTecnicosDr($total_tecnicos_dr): void
    {
        $this->total_tecnicos_dr = $total_tecnicos_dr;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoTotalCubierta()
    {
        return $this->profesores_tiempo_completo_total_cubierta;
    }

    /**
     * @param mixed $profesores_tiempo_completo_total_cubierta
     */
    public function setProfesoresTiempoCompletoTotalCubierta($profesores_tiempo_completo_total_cubierta): void
    {
        $this->profesores_tiempo_completo_total_cubierta = $profesores_tiempo_completo_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoDeEllosFem()
    {
        return $this->profesores_tiempo_completo_de_ellos_fem;
    }

    /**
     * @param mixed $profesores_tiempo_completo_de_ellos_fem
     */
    public function setProfesoresTiempoCompletoDeEllosFem($profesores_tiempo_completo_de_ellos_fem): void
    {
        $this->profesores_tiempo_completo_de_ellos_fem = $profesores_tiempo_completo_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoJovenesTotal()
    {
        return $this->profesores_tiempo_completo_jovenes_total;
    }

    /**
     * @param mixed $profesores_tiempo_completo_jovenes_total
     */
    public function setProfesoresTiempoCompletoJovenesTotal($profesores_tiempo_completo_jovenes_total): void
    {
        $this->profesores_tiempo_completo_jovenes_total = $profesores_tiempo_completo_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoJovenesFem()
    {
        return $this->profesores_tiempo_completo_jovenes_fem;
    }

    /**
     * @param mixed $profesores_tiempo_completo_jovenes_fem
     */
    public function setProfesoresTiempoCompletoJovenesFem($profesores_tiempo_completo_jovenes_fem): void
    {
        $this->profesores_tiempo_completo_jovenes_fem = $profesores_tiempo_completo_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoFem()
    {
        return $this->profesores_tiempo_completo_fem;
    }

    /**
     * @param mixed $profesores_tiempo_completo_fem
     */
    public function setProfesoresTiempoCompletoFem($profesores_tiempo_completo_fem): void
    {
        $this->profesores_tiempo_completo_fem = $profesores_tiempo_completo_fem;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoPt()
    {
        return $this->profesores_tiempo_completo_pt;
    }

    /**
     * @param mixed $profesores_tiempo_completo_pt
     */
    public function setProfesoresTiempoCompletoPt($profesores_tiempo_completo_pt): void
    {
        $this->profesores_tiempo_completo_pt = $profesores_tiempo_completo_pt;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoPa()
    {
        return $this->profesores_tiempo_completo_pa;
    }

    /**
     * @param mixed $profesores_tiempo_completo_pa
     */
    public function setProfesoresTiempoCompletoPa($profesores_tiempo_completo_pa): void
    {
        $this->profesores_tiempo_completo_pa = $profesores_tiempo_completo_pa;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoAs()
    {
        return $this->profesores_tiempo_completo_as;
    }

    /**
     * @param mixed $profesores_tiempo_completo_as
     */
    public function setProfesoresTiempoCompletoAs($profesores_tiempo_completo_as): void
    {
        $this->profesores_tiempo_completo_as = $profesores_tiempo_completo_as;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoI()
    {
        return $this->profesores_tiempo_completo_i;
    }

    /**
     * @param mixed $profesores_tiempo_completo_i
     */
    public function setProfesoresTiempoCompletoI($profesores_tiempo_completo_i): void
    {
        $this->profesores_tiempo_completo_i = $profesores_tiempo_completo_i;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoIt()
    {
        return $this->profesores_tiempo_completo_it;
    }

    /**
     * @param mixed $profesores_tiempo_completo_it
     */
    public function setProfesoresTiempoCompletoIt($profesores_tiempo_completo_it): void
    {
        $this->profesores_tiempo_completo_it = $profesores_tiempo_completo_it;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoIa()
    {
        return $this->profesores_tiempo_completo_ia;
    }

    /**
     * @param mixed $profesores_tiempo_completo_ia
     */
    public function setProfesoresTiempoCompletoIa($profesores_tiempo_completo_ia): void
    {
        $this->profesores_tiempo_completo_ia = $profesores_tiempo_completo_ia;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoIag()
    {
        return $this->profesores_tiempo_completo_iag;
    }

    /**
     * @param mixed $profesores_tiempo_completo_iag
     */
    public function setProfesoresTiempoCompletoIag($profesores_tiempo_completo_iag): void
    {
        $this->profesores_tiempo_completo_iag = $profesores_tiempo_completo_iag;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoAi()
    {
        return $this->profesores_tiempo_completo_ai;
    }

    /**
     * @param mixed $profesores_tiempo_completo_ai
     */
    public function setProfesoresTiempoCompletoAi($profesores_tiempo_completo_ai): void
    {
        $this->profesores_tiempo_completo_ai = $profesores_tiempo_completo_ai;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoAuxTecDoc()
    {
        return $this->profesores_tiempo_completo_aux_tec_doc;
    }

    /**
     * @param mixed $profesores_tiempo_completo_aux_tec_doc
     */
    public function setProfesoresTiempoCompletoAuxTecDoc($profesores_tiempo_completo_aux_tec_doc): void
    {
        $this->profesores_tiempo_completo_aux_tec_doc = $profesores_tiempo_completo_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoMsc()
    {
        return $this->profesores_tiempo_completo_msc;
    }

    /**
     * @param mixed $profesores_tiempo_completo_msc
     */
    public function setProfesoresTiempoCompletoMsc($profesores_tiempo_completo_msc): void
    {
        $this->profesores_tiempo_completo_msc = $profesores_tiempo_completo_msc;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTiempoCompletoDr()
    {
        return $this->profesores_tiempo_completo_dr;
    }

    /**
     * @param mixed $profesores_tiempo_completo_dr
     */
    public function setProfesoresTiempoCompletoDr($profesores_tiempo_completo_dr): void
    {
        $this->profesores_tiempo_completo_dr = $profesores_tiempo_completo_dr;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosTotalCubierta()
    {
        return $this->asesores_metodologos_total_cubierta;
    }

    /**
     * @param mixed $asesores_metodologos_total_cubierta
     */
    public function setAsesoresMetodologosTotalCubierta($asesores_metodologos_total_cubierta): void
    {
        $this->asesores_metodologos_total_cubierta = $asesores_metodologos_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosDeEllosFem()
    {
        return $this->asesores_metodologos_de_ellos_fem;
    }

    /**
     * @param mixed $asesores_metodologos_de_ellos_fem
     */
    public function setAsesoresMetodologosDeEllosFem($asesores_metodologos_de_ellos_fem): void
    {
        $this->asesores_metodologos_de_ellos_fem = $asesores_metodologos_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosJovenesTotal()
    {
        return $this->asesores_metodologos_jovenes_total;
    }

    /**
     * @param mixed $asesores_metodologos_jovenes_total
     */
    public function setAsesoresMetodologosJovenesTotal($asesores_metodologos_jovenes_total): void
    {
        $this->asesores_metodologos_jovenes_total = $asesores_metodologos_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosJovenesFem()
    {
        return $this->asesores_metodologos_jovenes_fem;
    }

    /**
     * @param mixed $asesores_metodologos_jovenes_fem
     */
    public function setAsesoresMetodologosJovenesFem($asesores_metodologos_jovenes_fem): void
    {
        $this->asesores_metodologos_jovenes_fem = $asesores_metodologos_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosFem()
    {
        return $this->asesores_metodologos_fem;
    }

    /**
     * @param mixed $asesores_metodologos_fem
     */
    public function setAsesoresMetodologosFem($asesores_metodologos_fem): void
    {
        $this->asesores_metodologos_fem = $asesores_metodologos_fem;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosPt()
    {
        return $this->asesores_metodologos_pt;
    }

    /**
     * @param mixed $asesores_metodologos_pt
     */
    public function setAsesoresMetodologosPt($asesores_metodologos_pt): void
    {
        $this->asesores_metodologos_pt = $asesores_metodologos_pt;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosPa()
    {
        return $this->asesores_metodologos_pa;
    }

    /**
     * @param mixed $asesores_metodologos_pa
     */
    public function setAsesoresMetodologosPa($asesores_metodologos_pa): void
    {
        $this->asesores_metodologos_pa = $asesores_metodologos_pa;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosAs()
    {
        return $this->asesores_metodologos_as;
    }

    /**
     * @param mixed $asesores_metodologos_as
     */
    public function setAsesoresMetodologosAs($asesores_metodologos_as): void
    {
        $this->asesores_metodologos_as = $asesores_metodologos_as;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosI()
    {
        return $this->asesores_metodologos_i;
    }

    /**
     * @param mixed $asesores_metodologos_i
     */
    public function setAsesoresMetodologosI($asesores_metodologos_i): void
    {
        $this->asesores_metodologos_i = $asesores_metodologos_i;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosIt()
    {
        return $this->asesores_metodologos_it;
    }

    /**
     * @param mixed $asesores_metodologos_it
     */
    public function setAsesoresMetodologosIt($asesores_metodologos_it): void
    {
        $this->asesores_metodologos_it = $asesores_metodologos_it;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosIa()
    {
        return $this->asesores_metodologos_ia;
    }

    /**
     * @param mixed $asesores_metodologos_ia
     */
    public function setAsesoresMetodologosIa($asesores_metodologos_ia): void
    {
        $this->asesores_metodologos_ia = $asesores_metodologos_ia;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosIag()
    {
        return $this->asesores_metodologos_iag;
    }

    /**
     * @param mixed $asesores_metodologos_iag
     */
    public function setAsesoresMetodologosIag($asesores_metodologos_iag): void
    {
        $this->asesores_metodologos_iag = $asesores_metodologos_iag;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosAi()
    {
        return $this->asesores_metodologos_ai;
    }

    /**
     * @param mixed $asesores_metodologos_ai
     */
    public function setAsesoresMetodologosAi($asesores_metodologos_ai): void
    {
        $this->asesores_metodologos_ai = $asesores_metodologos_ai;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosAuxTecDoc()
    {
        return $this->asesores_metodologos_aux_tec_doc;
    }

    /**
     * @param mixed $asesores_metodologos_aux_tec_doc
     */
    public function setAsesoresMetodologosAuxTecDoc($asesores_metodologos_aux_tec_doc): void
    {
        $this->asesores_metodologos_aux_tec_doc = $asesores_metodologos_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosMsc()
    {
        return $this->asesores_metodologos_msc;
    }

    /**
     * @param mixed $asesores_metodologos_msc
     */
    public function setAsesoresMetodologosMsc($asesores_metodologos_msc): void
    {
        $this->asesores_metodologos_msc = $asesores_metodologos_msc;
    }

    /**
     * @return mixed
     */
    public function getAsesoresMetodologosDr()
    {
        return $this->asesores_metodologos_dr;
    }

    /**
     * @param mixed $asesores_metodologos_dr
     */
    public function setAsesoresMetodologosDr($asesores_metodologos_dr): void
    {
        $this->asesores_metodologos_dr = $asesores_metodologos_dr;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresTotalCubierta()
    {
        return $this->investigadores_total_cubierta;
    }

    /**
     * @param mixed $investigadores_total_cubierta
     */
    public function setInvestigadoresTotalCubierta($investigadores_total_cubierta): void
    {
        $this->investigadores_total_cubierta = $investigadores_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresDeEllosFem()
    {
        return $this->investigadores_de_ellos_fem;
    }

    /**
     * @param mixed $investigadores_de_ellos_fem
     */
    public function setInvestigadoresDeEllosFem($investigadores_de_ellos_fem): void
    {
        $this->investigadores_de_ellos_fem = $investigadores_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresJovenesTotal()
    {
        return $this->investigadores_jovenes_total;
    }

    /**
     * @param mixed $investigadores_jovenes_total
     */
    public function setInvestigadoresJovenesTotal($investigadores_jovenes_total): void
    {
        $this->investigadores_jovenes_total = $investigadores_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresJovenesFem()
    {
        return $this->investigadores_jovenes_fem;
    }

    /**
     * @param mixed $investigadores_jovenes_fem
     */
    public function setInvestigadoresJovenesFem($investigadores_jovenes_fem): void
    {
        $this->investigadores_jovenes_fem = $investigadores_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresFem()
    {
        return $this->investigadores_fem;
    }

    /**
     * @param mixed $investigadores_fem
     */
    public function setInvestigadoresFem($investigadores_fem): void
    {
        $this->investigadores_fem = $investigadores_fem;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresPt()
    {
        return $this->investigadores_pt;
    }

    /**
     * @param mixed $investigadores_pt
     */
    public function setInvestigadoresPt($investigadores_pt): void
    {
        $this->investigadores_pt = $investigadores_pt;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresPa()
    {
        return $this->investigadores_pa;
    }

    /**
     * @param mixed $investigadores_pa
     */
    public function setInvestigadoresPa($investigadores_pa): void
    {
        $this->investigadores_pa = $investigadores_pa;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresAs()
    {
        return $this->investigadores_as;
    }

    /**
     * @param mixed $investigadores_as
     */
    public function setInvestigadoresAs($investigadores_as): void
    {
        $this->investigadores_as = $investigadores_as;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresI()
    {
        return $this->investigadores_i;
    }

    /**
     * @param mixed $investigadores_i
     */
    public function setInvestigadoresI($investigadores_i): void
    {
        $this->investigadores_i = $investigadores_i;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresIt()
    {
        return $this->investigadores_it;
    }

    /**
     * @param mixed $investigadores_it
     */
    public function setInvestigadoresIt($investigadores_it): void
    {
        $this->investigadores_it = $investigadores_it;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresIa()
    {
        return $this->investigadores_ia;
    }

    /**
     * @param mixed $investigadores_ia
     */
    public function setInvestigadoresIa($investigadores_ia): void
    {
        $this->investigadores_ia = $investigadores_ia;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresIag()
    {
        return $this->investigadores_iag;
    }

    /**
     * @param mixed $investigadores_iag
     */
    public function setInvestigadoresIag($investigadores_iag): void
    {
        $this->investigadores_iag = $investigadores_iag;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresAi()
    {
        return $this->investigadores_ai;
    }

    /**
     * @param mixed $investigadores_ai
     */
    public function setInvestigadoresAi($investigadores_ai): void
    {
        $this->investigadores_ai = $investigadores_ai;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresAuxTecDoc()
    {
        return $this->investigadores_aux_tec_doc;
    }

    /**
     * @param mixed $investigadores_aux_tec_doc
     */
    public function setInvestigadoresAuxTecDoc($investigadores_aux_tec_doc): void
    {
        $this->investigadores_aux_tec_doc = $investigadores_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresMsc()
    {
        return $this->investigadores_msc;
    }

    /**
     * @param mixed $investigadores_msc
     */
    public function setInvestigadoresMsc($investigadores_msc): void
    {
        $this->investigadores_msc = $investigadores_msc;
    }

    /**
     * @return mixed
     */
    public function getInvestigadoresDr()
    {
        return $this->investigadores_dr;
    }

    /**
     * @param mixed $investigadores_dr
     */
    public function setInvestigadoresDr($investigadores_dr): void
    {
        $this->investigadores_dr = $investigadores_dr;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosTotalCubierta()
    {
        return $this->otros_tecnicos_total_cubierta;
    }

    /**
     * @param mixed $otros_tecnicos_total_cubierta
     */
    public function setOtrosTecnicosTotalCubierta($otros_tecnicos_total_cubierta): void
    {
        $this->otros_tecnicos_total_cubierta = $otros_tecnicos_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosDeEllosFem()
    {
        return $this->otros_tecnicos_de_ellos_fem;
    }

    /**
     * @param mixed $otros_tecnicos_de_ellos_fem
     */
    public function setOtrosTecnicosDeEllosFem($otros_tecnicos_de_ellos_fem): void
    {
        $this->otros_tecnicos_de_ellos_fem = $otros_tecnicos_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosJovenesTotal()
    {
        return $this->otros_tecnicos_jovenes_total;
    }

    /**
     * @param mixed $otros_tecnicos_jovenes_total
     */
    public function setOtrosTecnicosJovenesTotal($otros_tecnicos_jovenes_total): void
    {
        $this->otros_tecnicos_jovenes_total = $otros_tecnicos_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosJovenesFem()
    {
        return $this->otros_tecnicos_jovenes_fem;
    }

    /**
     * @param mixed $otros_tecnicos_jovenes_fem
     */
    public function setOtrosTecnicosJovenesFem($otros_tecnicos_jovenes_fem): void
    {
        $this->otros_tecnicos_jovenes_fem = $otros_tecnicos_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosFem()
    {
        return $this->otros_tecnicos_fem;
    }

    /**
     * @param mixed $otros_tecnicos_fem
     */
    public function setOtrosTecnicosFem($otros_tecnicos_fem): void
    {
        $this->otros_tecnicos_fem = $otros_tecnicos_fem;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosPt()
    {
        return $this->otros_tecnicos_pt;
    }

    /**
     * @param mixed $otros_tecnicos_pt
     */
    public function setOtrosTecnicosPt($otros_tecnicos_pt): void
    {
        $this->otros_tecnicos_pt = $otros_tecnicos_pt;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosPa()
    {
        return $this->otros_tecnicos_pa;
    }

    /**
     * @param mixed $otros_tecnicos_pa
     */
    public function setOtrosTecnicosPa($otros_tecnicos_pa): void
    {
        $this->otros_tecnicos_pa = $otros_tecnicos_pa;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosAs()
    {
        return $this->otros_tecnicos_as;
    }

    /**
     * @param mixed $otros_tecnicos_as
     */
    public function setOtrosTecnicosAs($otros_tecnicos_as): void
    {
        $this->otros_tecnicos_as = $otros_tecnicos_as;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosI()
    {
        return $this->otros_tecnicos_i;
    }

    /**
     * @param mixed $otros_tecnicos_i
     */
    public function setOtrosTecnicosI($otros_tecnicos_i): void
    {
        $this->otros_tecnicos_i = $otros_tecnicos_i;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosIt()
    {
        return $this->otros_tecnicos_it;
    }

    /**
     * @param mixed $otros_tecnicos_it
     */
    public function setOtrosTecnicosIt($otros_tecnicos_it): void
    {
        $this->otros_tecnicos_it = $otros_tecnicos_it;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosIa()
    {
        return $this->otros_tecnicos_ia;
    }

    /**
     * @param mixed $otros_tecnicos_ia
     */
    public function setOtrosTecnicosIa($otros_tecnicos_ia): void
    {
        $this->otros_tecnicos_ia = $otros_tecnicos_ia;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosIag()
    {
        return $this->otros_tecnicos_iag;
    }

    /**
     * @param mixed $otros_tecnicos_iag
     */
    public function setOtrosTecnicosIag($otros_tecnicos_iag): void
    {
        $this->otros_tecnicos_iag = $otros_tecnicos_iag;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosAi()
    {
        return $this->otros_tecnicos_ai;
    }

    /**
     * @param mixed $otros_tecnicos_ai
     */
    public function setOtrosTecnicosAi($otros_tecnicos_ai): void
    {
        $this->otros_tecnicos_ai = $otros_tecnicos_ai;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosAuxTecDoc()
    {
        return $this->otros_tecnicos_aux_tec_doc;
    }

    /**
     * @param mixed $otros_tecnicos_aux_tec_doc
     */
    public function setOtrosTecnicosAuxTecDoc($otros_tecnicos_aux_tec_doc): void
    {
        $this->otros_tecnicos_aux_tec_doc = $otros_tecnicos_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosMsc()
    {
        return $this->otros_tecnicos_msc;
    }

    /**
     * @param mixed $otros_tecnicos_msc
     */
    public function setOtrosTecnicosMsc($otros_tecnicos_msc): void
    {
        $this->otros_tecnicos_msc = $otros_tecnicos_msc;
    }

    /**
     * @return mixed
     */
    public function getOtrosTecnicosDr()
    {
        return $this->otros_tecnicos_dr;
    }

    /**
     * @param mixed $otros_tecnicos_dr
     */
    public function setOtrosTecnicosDr($otros_tecnicos_dr): void
    {
        $this->otros_tecnicos_dr = $otros_tecnicos_dr;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosTotalCubierta()
    {
        return $this->administrativos_total_cubierta;
    }

    /**
     * @param mixed $administrativos_total_cubierta
     */
    public function setAdministrativosTotalCubierta($administrativos_total_cubierta): void
    {
        $this->administrativos_total_cubierta = $administrativos_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosDeEllosFem()
    {
        return $this->administrativos_de_ellos_fem;
    }

    /**
     * @param mixed $administrativos_de_ellos_fem
     */
    public function setAdministrativosDeEllosFem($administrativos_de_ellos_fem): void
    {
        $this->administrativos_de_ellos_fem = $administrativos_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosJovenesTotal()
    {
        return $this->administrativos_jovenes_total;
    }

    /**
     * @param mixed $administrativos_jovenes_total
     */
    public function setAdministrativosJovenesTotal($administrativos_jovenes_total): void
    {
        $this->administrativos_jovenes_total = $administrativos_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosJovenesFem()
    {
        return $this->administrativos_jovenes_fem;
    }

    /**
     * @param mixed $administrativos_jovenes_fem
     */
    public function setAdministrativosJovenesFem($administrativos_jovenes_fem): void
    {
        $this->administrativos_jovenes_fem = $administrativos_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosFem()
    {
        return $this->administrativos_fem;
    }

    /**
     * @param mixed $administrativos_fem
     */
    public function setAdministrativosFem($administrativos_fem): void
    {
        $this->administrativos_fem = $administrativos_fem;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosPt()
    {
        return $this->administrativos_pt;
    }

    /**
     * @param mixed $administrativos_pt
     */
    public function setAdministrativosPt($administrativos_pt): void
    {
        $this->administrativos_pt = $administrativos_pt;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosPa()
    {
        return $this->administrativos_pa;
    }

    /**
     * @param mixed $administrativos_pa
     */
    public function setAdministrativosPa($administrativos_pa): void
    {
        $this->administrativos_pa = $administrativos_pa;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosAs()
    {
        return $this->administrativos_as;
    }

    /**
     * @param mixed $administrativos_as
     */
    public function setAdministrativosAs($administrativos_as): void
    {
        $this->administrativos_as = $administrativos_as;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosI()
    {
        return $this->administrativos_i;
    }

    /**
     * @param mixed $administrativos_i
     */
    public function setAdministrativosI($administrativos_i): void
    {
        $this->administrativos_i = $administrativos_i;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosIt()
    {
        return $this->administrativos_it;
    }

    /**
     * @param mixed $administrativos_it
     */
    public function setAdministrativosIt($administrativos_it): void
    {
        $this->administrativos_it = $administrativos_it;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosIa()
    {
        return $this->administrativos_ia;
    }

    /**
     * @param mixed $administrativos_ia
     */
    public function setAdministrativosIa($administrativos_ia): void
    {
        $this->administrativos_ia = $administrativos_ia;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosIag()
    {
        return $this->administrativos_iag;
    }

    /**
     * @param mixed $administrativos_iag
     */
    public function setAdministrativosIag($administrativos_iag): void
    {
        $this->administrativos_iag = $administrativos_iag;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosAi()
    {
        return $this->administrativos_ai;
    }

    /**
     * @param mixed $administrativos_ai
     */
    public function setAdministrativosAi($administrativos_ai): void
    {
        $this->administrativos_ai = $administrativos_ai;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosAuxTecDoc()
    {
        return $this->administrativos_aux_tec_doc;
    }

    /**
     * @param mixed $administrativos_aux_tec_doc
     */
    public function setAdministrativosAuxTecDoc($administrativos_aux_tec_doc): void
    {
        $this->administrativos_aux_tec_doc = $administrativos_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosMsc()
    {
        return $this->administrativos_msc;
    }

    /**
     * @param mixed $administrativos_msc
     */
    public function setAdministrativosMsc($administrativos_msc): void
    {
        $this->administrativos_msc = $administrativos_msc;
    }

    /**
     * @return mixed
     */
    public function getAdministrativosDr()
    {
        return $this->administrativos_dr;
    }

    /**
     * @param mixed $administrativos_dr
     */
    public function setAdministrativosDr($administrativos_dr): void
    {
        $this->administrativos_dr = $administrativos_dr;
    }

    /**
     * @return mixed
     */
    public function getServicioTotalCubierta()
    {
        return $this->servicio_total_cubierta;
    }

    /**
     * @param mixed $servicio_total_cubierta
     */
    public function setServicioTotalCubierta($servicio_total_cubierta): void
    {
        $this->servicio_total_cubierta = $servicio_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getServicioDeEllosFem()
    {
        return $this->servicio_de_ellos_fem;
    }

    /**
     * @param mixed $servicio_de_ellos_fem
     */
    public function setServicioDeEllosFem($servicio_de_ellos_fem): void
    {
        $this->servicio_de_ellos_fem = $servicio_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getServicioJovenesTotal()
    {
        return $this->servicio_jovenes_total;
    }

    /**
     * @param mixed $servicio_jovenes_total
     */
    public function setServicioJovenesTotal($servicio_jovenes_total): void
    {
        $this->servicio_jovenes_total = $servicio_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getServicioJovenesFem()
    {
        return $this->servicio_jovenes_fem;
    }

    /**
     * @param mixed $servicio_jovenes_fem
     */
    public function setServicioJovenesFem($servicio_jovenes_fem): void
    {
        $this->servicio_jovenes_fem = $servicio_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getServicioFem()
    {
        return $this->servicio_fem;
    }

    /**
     * @param mixed $servicio_fem
     */
    public function setServicioFem($servicio_fem): void
    {
        $this->servicio_fem = $servicio_fem;
    }

    /**
     * @return mixed
     */
    public function getServicioPt()
    {
        return $this->servicio_pt;
    }

    /**
     * @param mixed $servicio_pt
     */
    public function setServicioPt($servicio_pt): void
    {
        $this->servicio_pt = $servicio_pt;
    }

    /**
     * @return mixed
     */
    public function getServicioPa()
    {
        return $this->servicio_pa;
    }

    /**
     * @param mixed $servicio_pa
     */
    public function setServicioPa($servicio_pa): void
    {
        $this->servicio_pa = $servicio_pa;
    }

    /**
     * @return mixed
     */
    public function getServicioAs()
    {
        return $this->servicio_as;
    }

    /**
     * @param mixed $servicio_as
     */
    public function setServicioAs($servicio_as): void
    {
        $this->servicio_as = $servicio_as;
    }

    /**
     * @return mixed
     */
    public function getServicioI()
    {
        return $this->servicio_i;
    }

    /**
     * @param mixed $servicio_i
     */
    public function setServicioI($servicio_i): void
    {
        $this->servicio_i = $servicio_i;
    }

    /**
     * @return mixed
     */
    public function getServicioIt()
    {
        return $this->servicio_it;
    }

    /**
     * @param mixed $servicio_it
     */
    public function setServicioIt($servicio_it): void
    {
        $this->servicio_it = $servicio_it;
    }

    /**
     * @return mixed
     */
    public function getServicioIa()
    {
        return $this->servicio_ia;
    }

    /**
     * @param mixed $servicio_ia
     */
    public function setServicioIa($servicio_ia): void
    {
        $this->servicio_ia = $servicio_ia;
    }

    /**
     * @return mixed
     */
    public function getServicioIag()
    {
        return $this->servicio_iag;
    }

    /**
     * @param mixed $servicio_iag
     */
    public function setServicioIag($servicio_iag): void
    {
        $this->servicio_iag = $servicio_iag;
    }

    /**
     * @return mixed
     */
    public function getServicioAi()
    {
        return $this->servicio_ai;
    }

    /**
     * @param mixed $servicio_ai
     */
    public function setServicioAi($servicio_ai): void
    {
        $this->servicio_ai = $servicio_ai;
    }

    /**
     * @return mixed
     */
    public function getServicioAuxTecDoc()
    {
        return $this->servicio_aux_tec_doc;
    }

    /**
     * @param mixed $servicio_aux_tec_doc
     */
    public function setServicioAuxTecDoc($servicio_aux_tec_doc): void
    {
        $this->servicio_aux_tec_doc = $servicio_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getServicioMsc()
    {
        return $this->servicio_msc;
    }

    /**
     * @param mixed $servicio_msc
     */
    public function setServicioMsc($servicio_msc): void
    {
        $this->servicio_msc = $servicio_msc;
    }

    /**
     * @return mixed
     */
    public function getServicioDr()
    {
        return $this->servicio_dr;
    }

    /**
     * @param mixed $servicio_dr
     */
    public function setServicioDr($servicio_dr): void
    {
        $this->servicio_dr = $servicio_dr;
    }

    /**
     * @return mixed
     */
    public function getOperariosTotalCubierta()
    {
        return $this->operarios_total_cubierta;
    }

    /**
     * @param mixed $operarios_total_cubierta
     */
    public function setOperariosTotalCubierta($operarios_total_cubierta): void
    {
        $this->operarios_total_cubierta = $operarios_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getOperariosDeEllosFem()
    {
        return $this->operarios_de_ellos_fem;
    }

    /**
     * @param mixed $operarios_de_ellos_fem
     */
    public function setOperariosDeEllosFem($operarios_de_ellos_fem): void
    {
        $this->operarios_de_ellos_fem = $operarios_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getOperariosJovenesTotal()
    {
        return $this->operarios_jovenes_total;
    }

    /**
     * @param mixed $operarios_jovenes_total
     */
    public function setOperariosJovenesTotal($operarios_jovenes_total): void
    {
        $this->operarios_jovenes_total = $operarios_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getOperariosJovenesFem()
    {
        return $this->operarios_jovenes_fem;
    }

    /**
     * @param mixed $operarios_jovenes_fem
     */
    public function setOperariosJovenesFem($operarios_jovenes_fem): void
    {
        $this->operarios_jovenes_fem = $operarios_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getOperariosFem()
    {
        return $this->operarios_fem;
    }

    /**
     * @param mixed $operarios_fem
     */
    public function setOperariosFem($operarios_fem): void
    {
        $this->operarios_fem = $operarios_fem;
    }

    /**
     * @return mixed
     */
    public function getOperariosPt()
    {
        return $this->operarios_pt;
    }

    /**
     * @param mixed $operarios_pt
     */
    public function setOperariosPt($operarios_pt): void
    {
        $this->operarios_pt = $operarios_pt;
    }

    /**
     * @return mixed
     */
    public function getOperariosPa()
    {
        return $this->operarios_pa;
    }

    /**
     * @param mixed $operarios_pa
     */
    public function setOperariosPa($operarios_pa): void
    {
        $this->operarios_pa = $operarios_pa;
    }

    /**
     * @return mixed
     */
    public function getOperariosAs()
    {
        return $this->operarios_as;
    }

    /**
     * @param mixed $operarios_as
     */
    public function setOperariosAs($operarios_as): void
    {
        $this->operarios_as = $operarios_as;
    }

    /**
     * @return mixed
     */
    public function getOperariosI()
    {
        return $this->operarios_i;
    }

    /**
     * @param mixed $operarios_i
     */
    public function setOperariosI($operarios_i): void
    {
        $this->operarios_i = $operarios_i;
    }

    /**
     * @return mixed
     */
    public function getOperariosIt()
    {
        return $this->operarios_it;
    }

    /**
     * @param mixed $operarios_it
     */
    public function setOperariosIt($operarios_it): void
    {
        $this->operarios_it = $operarios_it;
    }

    /**
     * @return mixed
     */
    public function getOperariosIa()
    {
        return $this->operarios_ia;
    }

    /**
     * @param mixed $operarios_ia
     */
    public function setOperariosIa($operarios_ia): void
    {
        $this->operarios_ia = $operarios_ia;
    }

    /**
     * @return mixed
     */
    public function getOperariosIag()
    {
        return $this->operarios_iag;
    }

    /**
     * @param mixed $operarios_iag
     */
    public function setOperariosIag($operarios_iag): void
    {
        $this->operarios_iag = $operarios_iag;
    }

    /**
     * @return mixed
     */
    public function getOperariosAi()
    {
        return $this->operarios_ai;
    }

    /**
     * @param mixed $operarios_ai
     */
    public function setOperariosAi($operarios_ai): void
    {
        $this->operarios_ai = $operarios_ai;
    }

    /**
     * @return mixed
     */
    public function getOperariosAuxTecDoc()
    {
        return $this->operarios_aux_tec_doc;
    }

    /**
     * @param mixed $operarios_aux_tec_doc
     */
    public function setOperariosAuxTecDoc($operarios_aux_tec_doc): void
    {
        $this->operarios_aux_tec_doc = $operarios_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getOperariosMsc()
    {
        return $this->operarios_msc;
    }

    /**
     * @param mixed $operarios_msc
     */
    public function setOperariosMsc($operarios_msc): void
    {
        $this->operarios_msc = $operarios_msc;
    }

    /**
     * @return mixed
     */
    public function getOperariosDr()
    {
        return $this->operarios_dr;
    }

    /**
     * @param mixed $operarios_dr
     */
    public function setOperariosDr($operarios_dr): void
    {
        $this->operarios_dr = $operarios_dr;
    }

    /**
     * @return mixed
     */
    public function getTotalTotalCubierta()
    {
        return $this->total_total_cubierta;
    }

    /**
     * @param mixed $total_total_cubierta
     */
    public function setTotalTotalCubierta($total_total_cubierta): void
    {
        $this->total_total_cubierta = $total_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getTotalDeEllosFem()
    {
        return $this->total_de_ellos_fem;
    }

    /**
     * @param mixed $total_de_ellos_fem
     */
    public function setTotalDeEllosFem($total_de_ellos_fem): void
    {
        $this->total_de_ellos_fem = $total_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalJovenesTotal()
    {
        return $this->total_jovenes_total;
    }

    /**
     * @param mixed $total_jovenes_total
     */
    public function setTotalJovenesTotal($total_jovenes_total): void
    {
        $this->total_jovenes_total = $total_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getTotalJovenesFem()
    {
        return $this->total_jovenes_fem;
    }

    /**
     * @param mixed $total_jovenes_fem
     */
    public function setTotalJovenesFem($total_jovenes_fem): void
    {
        $this->total_jovenes_fem = $total_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalFem()
    {
        return $this->total_fem;
    }

    /**
     * @param mixed $total_fem
     */
    public function setTotalFem($total_fem): void
    {
        $this->total_fem = $total_fem;
    }

    /**
     * @return mixed
     */
    public function getTotalPt()
    {
        return $this->total_pt;
    }

    /**
     * @param mixed $total_pt
     */
    public function setTotalPt($total_pt): void
    {
        $this->total_pt = $total_pt;
    }

    /**
     * @return mixed
     */
    public function getTotalPa()
    {
        return $this->total_pa;
    }

    /**
     * @param mixed $total_pa
     */
    public function setTotalPa($total_pa): void
    {
        $this->total_pa = $total_pa;
    }

    /**
     * @return mixed
     */
    public function getTotalAs()
    {
        return $this->total_as;
    }

    /**
     * @param mixed $total_as
     */
    public function setTotalAs($total_as): void
    {
        $this->total_as = $total_as;
    }

    /**
     * @return mixed
     */
    public function getTotalI()
    {
        return $this->total_i;
    }

    /**
     * @param mixed $total_i
     */
    public function setTotalI($total_i): void
    {
        $this->total_i = $total_i;
    }

    /**
     * @return mixed
     */
    public function getTotalIt()
    {
        return $this->total_it;
    }

    /**
     * @param mixed $total_it
     */
    public function setTotalIt($total_it): void
    {
        $this->total_it = $total_it;
    }

    /**
     * @return mixed
     */
    public function getTotalIa()
    {
        return $this->total_ia;
    }

    /**
     * @param mixed $total_ia
     */
    public function setTotalIa($total_ia): void
    {
        $this->total_ia = $total_ia;
    }

    /**
     * @return mixed
     */
    public function getTotalIag()
    {
        return $this->total_iag;
    }

    /**
     * @param mixed $total_iag
     */
    public function setTotalIag($total_iag): void
    {
        $this->total_iag = $total_iag;
    }

    /**
     * @return mixed
     */
    public function getTotalAi()
    {
        return $this->total_ai;
    }

    /**
     * @param mixed $total_ai
     */
    public function setTotalAi($total_ai): void
    {
        $this->total_ai = $total_ai;
    }

    /**
     * @return mixed
     */
    public function getTotalAuxTecDoc()
    {
        return $this->total_aux_tec_doc;
    }

    /**
     * @param mixed $total_aux_tec_doc
     */
    public function setTotalAuxTecDoc($total_aux_tec_doc): void
    {
        $this->total_aux_tec_doc = $total_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getTotalMsc()
    {
        return $this->total_msc;
    }

    /**
     * @param mixed $total_msc
     */
    public function setTotalMsc($total_msc): void
    {
        $this->total_msc = $total_msc;
    }

    /**
     * @return mixed
     */
    public function getTotalDr()
    {
        return $this->total_dr;
    }

    /**
     * @param mixed $total_dr
     */
    public function setTotalDr($total_dr): void
    {
        $this->total_dr = $total_dr;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialTotalCubierta()
    {
        return $this->profesores_tipo_parcial_total_cubierta;
    }

    /**
     * @param mixed $profesores_tipo_parcial_total_cubierta
     */
    public function setProfesoresTipoParcialTotalCubierta($profesores_tipo_parcial_total_cubierta): void
    {
        $this->profesores_tipo_parcial_total_cubierta = $profesores_tipo_parcial_total_cubierta;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialDeEllosFem()
    {
        return $this->profesores_tipo_parcial_de_ellos_fem;
    }

    /**
     * @param mixed $profesores_tipo_parcial_de_ellos_fem
     */
    public function setProfesoresTipoParcialDeEllosFem($profesores_tipo_parcial_de_ellos_fem): void
    {
        $this->profesores_tipo_parcial_de_ellos_fem = $profesores_tipo_parcial_de_ellos_fem;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialJovenesTotal()
    {
        return $this->profesores_tipo_parcial_jovenes_total;
    }

    /**
     * @param mixed $profesores_tipo_parcial_jovenes_total
     */
    public function setProfesoresTipoParcialJovenesTotal($profesores_tipo_parcial_jovenes_total): void
    {
        $this->profesores_tipo_parcial_jovenes_total = $profesores_tipo_parcial_jovenes_total;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialJovenesFem()
    {
        return $this->profesores_tipo_parcial_jovenes_fem;
    }

    /**
     * @param mixed $profesores_tipo_parcial_jovenes_fem
     */
    public function setProfesoresTipoParcialJovenesFem($profesores_tipo_parcial_jovenes_fem): void
    {
        $this->profesores_tipo_parcial_jovenes_fem = $profesores_tipo_parcial_jovenes_fem;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialFem()
    {
        return $this->profesores_tipo_parcial_fem;
    }

    /**
     * @param mixed $profesores_tipo_parcial_fem
     */
    public function setProfesoresTipoParcialFem($profesores_tipo_parcial_fem): void
    {
        $this->profesores_tipo_parcial_fem = $profesores_tipo_parcial_fem;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialPt()
    {
        return $this->profesores_tipo_parcial_pt;
    }

    /**
     * @param mixed $profesores_tipo_parcial_pt
     */
    public function setProfesoresTipoParcialPt($profesores_tipo_parcial_pt): void
    {
        $this->profesores_tipo_parcial_pt = $profesores_tipo_parcial_pt;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialPa()
    {
        return $this->profesores_tipo_parcial_pa;
    }

    /**
     * @param mixed $profesores_tipo_parcial_pa
     */
    public function setProfesoresTipoParcialPa($profesores_tipo_parcial_pa): void
    {
        $this->profesores_tipo_parcial_pa = $profesores_tipo_parcial_pa;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialAs()
    {
        return $this->profesores_tipo_parcial_as;
    }

    /**
     * @param mixed $profesores_tipo_parcial_as
     */
    public function setProfesoresTipoParcialAs($profesores_tipo_parcial_as): void
    {
        $this->profesores_tipo_parcial_as = $profesores_tipo_parcial_as;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialI()
    {
        return $this->profesores_tipo_parcial_i;
    }

    /**
     * @param mixed $profesores_tipo_parcial_i
     */
    public function setProfesoresTipoParcialI($profesores_tipo_parcial_i): void
    {
        $this->profesores_tipo_parcial_i = $profesores_tipo_parcial_i;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialIt()
    {
        return $this->profesores_tipo_parcial_it;
    }

    /**
     * @param mixed $profesores_tipo_parcial_it
     */
    public function setProfesoresTipoParcialIt($profesores_tipo_parcial_it): void
    {
        $this->profesores_tipo_parcial_it = $profesores_tipo_parcial_it;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialIa()
    {
        return $this->profesores_tipo_parcial_ia;
    }

    /**
     * @param mixed $profesores_tipo_parcial_ia
     */
    public function setProfesoresTipoParcialIa($profesores_tipo_parcial_ia): void
    {
        $this->profesores_tipo_parcial_ia = $profesores_tipo_parcial_ia;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialIag()
    {
        return $this->profesores_tipo_parcial_iag;
    }

    /**
     * @param mixed $profesores_tipo_parcial_iag
     */
    public function setProfesoresTipoParcialIag($profesores_tipo_parcial_iag): void
    {
        $this->profesores_tipo_parcial_iag = $profesores_tipo_parcial_iag;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialAi()
    {
        return $this->profesores_tipo_parcial_ai;
    }

    /**
     * @param mixed $profesores_tipo_parcial_ai
     */
    public function setProfesoresTipoParcialAi($profesores_tipo_parcial_ai): void
    {
        $this->profesores_tipo_parcial_ai = $profesores_tipo_parcial_ai;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialAuxTecDoc()
    {
        return $this->profesores_tipo_parcial_aux_tec_doc;
    }

    /**
     * @param mixed $profesores_tipo_parcial_aux_tec_doc
     */
    public function setProfesoresTipoParcialAuxTecDoc($profesores_tipo_parcial_aux_tec_doc): void
    {
        $this->profesores_tipo_parcial_aux_tec_doc = $profesores_tipo_parcial_aux_tec_doc;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialMsc()
    {
        return $this->profesores_tipo_parcial_msc;
    }

    /**
     * @param mixed $profesores_tipo_parcial_msc
     */
    public function setProfesoresTipoParcialMsc($profesores_tipo_parcial_msc): void
    {
        $this->profesores_tipo_parcial_msc = $profesores_tipo_parcial_msc;
    }

    /**
     * @return mixed
     */
    public function getProfesoresTipoParcialDr()
    {
        return $this->profesores_tipo_parcial_dr;
    }

    /**
     * @param mixed $profesores_tipo_parcial_dr
     */
    public function setProfesoresTipoParcialDr($profesores_tipo_parcial_dr): void
    {
        $this->profesores_tipo_parcial_dr = $profesores_tipo_parcial_dr;
    }


}