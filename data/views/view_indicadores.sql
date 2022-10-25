-- View: planificacion.view_indicadores

-- DROP VIEW planificacion.view_indicadores;

CREATE OR REPLACE VIEW planificacion.view_indicadores
 AS
 SELECT indicador.id AS indicador_id,
    indicador.nombre AS indicador_nombre,
    indicador.seguimiento AS indicador_seguimiento,
    plan_indicador.id AS plan_indicador_id,
    unidad_medida.id AS unidad_medida_id,
    unidad_medida.siglas AS unidad_medida_siglas,
    unidad_medida.nombre AS unidad_medida_nombre,
    plan.id AS plan_id,
    plan.nombre AS plan_nombre,
    plan.periodo AS plan_periodo,
    evaluacion.id AS evaluacion_id,
    evaluacion.siglas AS evaluacion_siglas,
    evaluacion.color AS evaluacion_color,
    objetivo_especifico.id AS objetivo_especifico_id,
    objetivo_especifico.nombre AS objetivo_especifico_nombre,
    objetivo_general.id AS objetivo_general_id,
    objetivo_general.nombre AS objetivo_general_nombre,
    responsable.id AS responsable_id,
    persona.id AS persona_id,
    concat_ws(' '::text, persona.primer_nombre, persona.segundo_nombre, persona.primer_apellido, persona.segundo_apellido) AS persona_nombre,
    estructura.id AS estructura_id,
    estructura.nombre AS estructura_nombre,
    provincia.id AS provincia_id,
    provincia.nombre AS provincia_nombre,
    municipio.id AS municipio_id,
    municipio.nombre AS municipio_nombre,
    indicador.ubicacion
   FROM planificacion.tbd_indicador indicador
     JOIN planificacion.tbn_unidad_medida unidad_medida ON unidad_medida.id = indicador.unidad_medida_id
     JOIN planificacion.tbr_plan_indicador plan_indicador ON plan_indicador.indicador_id = indicador.id
     JOIN planificacion.tbd_plan plan ON plan.id = plan_indicador.plan_id
     JOIN planificacion.tbr_plan_indicador_responsable indicador_responsable ON indicador_responsable.indicador_id = indicador.id
     JOIN planificacion.tbn_objetivo_especifico objetivo_especifico ON objetivo_especifico.id = indicador.objetivo_especifico_id
     JOIN planificacion.tbn_objetivo_general objetivo_general ON objetivo_general.id = objetivo_especifico.objetivo_general_id
     JOIN personal.tbd_responsable responsable ON responsable.id = indicador_responsable.responsable_id
     JOIN personal.tbd_persona persona ON persona.id = responsable.persona_id
     LEFT JOIN planificacion.tbn_evaluacion evaluacion ON evaluacion.id = plan_indicador.evaluacion_id
     LEFT JOIN planificacion.tbr_indicador_estructura indicador_estructura ON indicador_estructura.indicador_id = indicador.id
     LEFT JOIN estructura.tbd_estructura estructura ON estructura.id = indicador_estructura.estructura_id
     LEFT JOIN estructura.tbn_provincia provincia ON provincia.id = estructura.provincia_id
     LEFT JOIN estructura.tbn_municipio municipio ON municipio.id = estructura.municipio_id;

ALTER TABLE planificacion.view_indicadores
    OWNER TO postgres;

