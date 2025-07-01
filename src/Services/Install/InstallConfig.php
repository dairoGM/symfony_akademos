<?php

namespace App\Services\Install;

use App\Command\Install\Functionality;
use App\Command\Install\Module;
use App\Command\Install\Role;

class InstallConfig
{
    //Modulos -------------------------------------------------------------------------------------------

    /**
     * Define una Lista de Modulos
     *
     * For create one -> Module::createModule($moduleKey, string $name, string $description = null)
     *
     * @return Module[]
     */
    public static function defineModules(): array
    {
        $modules = array();

        //Administración
        $modules[] = Module::createModule("MODULE_ADMIN", "Módulo de Administración", "Módulo de administración");
        $modules[] = Module::createModule("MODULE_PERSON", "Módulo de Personal", "Módulo de personal");
        $modules[] = Module::createModule("MODULE_STRUCT", "Módulo de Estructura", "Módulo de estrucutra y composición");
        $modules[] = Module::createModule("MODULE_REPORT", "Módulo de Reportes", "Módulo de reportes");
        $modules[] = Module::createModule("MODULE_TRACE", "Módulo de Trazas", "Módulo de trazas");
        $modules[] = Module::createModule("MODULE_INSTIT", "Módulo de Intituciones", "Módulo de Intituciones");
        $modules[] = Module::createModule("MODULE_PREGR", "Módulo de Pregrado", "Módulo de Pregrado");
        $modules[] = Module::createModule("MODULE_POSTG", "Módulo de Postgrado", "Módulo de Postgrado");
        /***********************************DRI*************************************/
        $modules[] = Module::createModule("MODULE_CONVENIO", "Módulo de Convenio", "Módulo de Convenio");
        $modules[] = Module::createModule("MODULE_TRAMITE", "Módulo de Trámites", "Módulo de Trámites");
        $modules[] = Module::createModule("MODULE_COOPERACION", "Módulo de Cooperación", "Módulo de Cooperación");
        $modules[] = Module::createModule("MODULE_VISITA", "Módulo de Visitas", "Módulo de Visitas");
        $modules[] = Module::createModule("MODULE_ECONOMIA", "Módulo de Economía", "Módulo de Economía");

        /***********************************JAN*************************************/
        $modules[] = Module::createModule("MODULE_EVALUACION", "Módulo de Evaluación", "Módulo de Evaluación");

        /***********************************DENYS*************************************/
        $modules[] = Module::createModule("MODULE_INFORMATIZACION", "Módulo de Informatización", "Módulo de Informatización");
        $modules[] = Module::createModule("MODULE_RRHH", "Módulo de RRHH", "Módulo de RRHH");

        return $modules;
    }



    //Funcionaliades -------------------------------------------------------------------------------------------

    /**
     * Define una Lista de Funcionalidades para Comunes
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ADMIN", "Módulo de Administración")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesComunes(): array
    {
        $functionalities = array();

        //Administración
        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_PORTADA_ADMIN", "Portada de Administración", "Para el acceso principal al la Portada de Administración");

        return $functionalities;
    }


    /**
     * Define una Lista de Funcionalidades para Administracion
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ADMIN", "Módulo de Administración")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForAdministracion(): array
    {
        $functionalities = array();

        //Administración        
//        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_HOME_ADMIN", "Portada de Administración", "Portada Administración");
        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_GEST_USER", "Gestionar usuarios", "Gestionar usuarios");
        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_GEST_FUNC", "Gestionar funcionalidades", "Gestionar funcionalidades del sistema");
        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_GEST_MODULE", "Gestionar módulos", "Gestionar módulos del sistema");
        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_GEST_ROLES", "Gestionar roles", "Gestionar los roles del sistema");
        $functionalities[] = Functionality::createFunctionality("MODULE_ADMIN", "ROLE_GEST_NOTIF", "Gestionar notificaciones", "Gestionar las norificaciones del sistema");

        return $functionalities;
    }


    /**
     * Define una Lista de Funcionalidades para Personal
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_PERSONAL", "Módulo de Personal")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForPersonal(): array
    {
        $functionalities = array();

        //Personal
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_HOME_PERSONAL", "Portada de Personal", "Portada de Personal");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_CARRERA", "Gestionar carreras", "Gestionar carreras");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_CATDOC", "Gestionar categorías docentes", "Gestionar categorías docentes");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_CATINVST", "Gestionar categorías investigativas", "Gestionar categorías investigativas");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_CLAPER", "Gestionar clasificación de persona", "Gestionar clasificación de persona");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_GRADAKAD", "Gestionar grados académicos", "Gestionar grados académicos");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_NIVESC", "Gestionar nivel escolar", "Gestionar nivel escolar");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_ORGANZ", "Gestionar organizaciones", "Gestionar organizaciones");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_PERSON", "Directorio de personas", "Directorio de personas");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_PROF", "Gestionar profesiones", "Gestionar profesiones");
//        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_RESPONS", "Gestionar responsables", "Gestionar responsables");
        $functionalities[] = Functionality::createFunctionality("MODULE_PERSON", "ROLE_GEST_TYPORG", "Gestionar tipos de organización", "Gestionar tipos de organización");


        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Estructura
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Estructura")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForEstructura(): array
    {
        $functionalities = array();

        //Estructura        
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_HOME_STRUCT", "Portada de Estructura", "Portada de Estructura");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_CATGEST", "Gestionar categorías de estructura", "Gestionar categorías de estructura");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_CATRESP", "Gestionar categorías de responsabilidad", "Gestionar categorías de responsabilidad");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_ESTRUCT", "Gestionar estructuras", "Gestionar estructuras");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_MUNICP", "Gestionar municipios", "Gestionar municipios");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_PROVIN", "Gestionar provincias", "Gestionar provincias");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_RESPON", "Gestionar responsabilidades", "Gestionar responsabilidades");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_TYPEESTR", "Gestionar tipos de estructura", "Gestionar tipos de estructura");
        $functionalities[] = Functionality::createFunctionality("MODULE_STRUCT", "ROLE_GEST_ENTITY", "Gestionar entidades", "Gestionar entidades");


        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Instituciones
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Instituciones")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForInstituciones(): array
    {
        $functionalities = array();

        //Instituciones
        $functionalities[] = Functionality::createFunctionality("MODULE_INSTIT", "ROLE_HOME_INSTIT", "Portada de Instituciones", "Portada de Instituciones");
        $functionalities[] = Functionality::createFunctionality("MODULE_INSTIT", "ROLE_GEST_TIPINST", "Gestionar tipos de instituciones", "Gestionar tipos de instituciones");
        $functionalities[] = Functionality::createFunctionality("MODULE_INSTIT", "ROLE_GEST_CATACRED", "Gestionar categorias de acreditacion", "Gestionar categorias de acreditacion");
        $functionalities[] = Functionality::createFunctionality("MODULE_INSTIT", "ROLE_GEST_INSTIT", "Gestionar instituciones", "Gestionar instituciones");

        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Pregrado
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Pregrado")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForPregrado(): array
    {
        $functionalities = array();

        //Pregrado
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_HOME_PREGR", "Portada de Pregrado", "Portada de Pregrado");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_CURACAD", "Gestionar curso academico", "Gestionar curso academico");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_ESTPROGACAD", "Gestionar estado de programa academico", "Gestionar estado de programa academico");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_TIPOPROGACAD", "Gestionar tipo de programa academico", "Gestionar tipo de programa academico");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_TIPOORG", "Gestionar tipo de organismo", "Gestionar tipo de organismo");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_COMNACION", "Gestionar comision nacional", "Gestionar comision nacional");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_ORGDEMAND", "Gestionar organismo demandante", "Gestionar organismo demandante");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_ORGFORMA", "Gestionar organismo formador", "Gestionar organismo formador");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_DOCS", "Gestionar documentos", "Gestionar documentos");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_PLANEST", "Gestionar plan de estudio", "Gestionar plan de estudio");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_SOLPROG", "Gestionar solicitud de programas", "Gestionar solicitud de programas");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_PROGAPROB", "Gestionar programas aprobados", "Gestionar programas aprobados");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_GEST_PROGEXTINT", "Gestionar programas extintos", "Gestionar programas extintos");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_PREGRADO_REPORTE_PROG_APROBADOS", "Reporte de programas aprobados", "Reporte de programas aprobados");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_PREGRADO_REPORTE_PROG_POR_UNIVERSIDAD", "Reporte de programas académicos de pregrado por universidad", "Reporte de programas académicos de pregrado por universidad");
        $functionalities[] = Functionality::createFunctionality("MODULE_PREGR", "ROLE_PREGRADO_REPORTE_UNIVERSIDAD_POR_PROG", "Reporte de universidades por programas académicos de pregrado", "Reporte de universidades por programas académicos de pregrado");

        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Postgrado
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Postgrado")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForPostgrado(): array
    {
        $functionalities = array();

        //Postgrado
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_HOME_POSTG", "Portada de Posgrado", "Portada de Posgrado");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_SOLPROGRAM", "Gestionar solicitudes de programas", "Gestionar solicitudes de programas");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_GRADO_CIENTIFICO", "Gestionar grados científicos", "Gestionar grados científicos");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_FORMACION_ACADEMICA", "Gestionar formación académica de posgrado", "Gestionar formación académica de posgrado");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_COPEP", "Gestionar copep", "Gestionar copep");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_COMISION", "Gestionar comision", "Gestionar comision");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_TIPPROGRAM", "Gestionar tipo de programa", "Gestionar tipo de programa");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_RAMCIENC", "Gestionar rama de la ciencia", "Gestionar rama de la ciencia");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_CENTRO_AUTORIZADO", "Centros autorizados de posgrado", "Centros autorizados de posgrado");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_ROLESPOS ", "Gestionar roles", "Gestionar roles");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_TIP_SOLICITUD_POST ", "Gestionar tipos de solicitudes", "Gestionar tipos de solicitudes");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_REPORTE_FORMACION_ACADEMICA", "Reporte de Formación académica de posgrado", "Reporte de Formación académica de posgrado");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_REPORTE_GRADO_CIENTIFICO", "Reporte de Grados científicos ", "Reporte de Grados científicos ");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_FORMACION_DOCTORES ", "Gestionar IAFD (Formación de Doctores) ", "Gestionar IAFD (Formación de Doctores) ");

        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Convenio
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Convenio")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForConvenio(): array
    {
        $functionalities = array();

        //Convenio
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_HOME_CONVENIO", "Portada de Convenio", "Portada de Convenio");
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_GEST_TIPO", "Gestión de tipos de convenios", "Gestión de tipos de convenios");
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_GEST_MODALIDAD", "Gestión de modalidades de convenios", "Gestión de modalidades de convenios");
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_GEST_REGION", "Gestión de regiones de convenios", "Gestión de modalidades de convenios");
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_GEST_CONVENIO", "Gestión de convenios", "Gestión de convenios");
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_GEST_INST_EXTRANJERA", "Gestión de institución extranjera", "Gestión de institución extranjera");
        return $functionalities;
    }


    /**
     * Define una Lista de Funcionalidades para Tramites
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Tramites")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForTramites(): array
    {
        $functionalities = array();

        //Tramites
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_HOME_TRAMITES", "Portada de Trámites", "Portada de Trámites");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_TIPO_PASAPORTE", "Gestión de tipos de pasaportes", "Gestión de tipos de pasaportes");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_PLAN_MISION", "Gestión de planes de misión", "Gestión de planes de misión");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_PLAN_MISION_DETALLES", "Listado de personas en plan de misión", "Listado de personas en plan de misión");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_CONCEPTO_SALIDA", "Gestión de conceptos de salida", "Gestión de conceptos de salida");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_ESTADO_SALIDA", "Gestión de estados de salida", "Gestión de estados de salida");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_TRAMITE", "Gestión de trámites", "Gestión de trámites");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_FICHA_SALIDA", "Gestión de ficha de salida", "Gestión de ficha de salida");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_DOCUMENTO_SALIDA", "Gestión de documento de salida", "Gestión de documento de salida");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_PASAPORTE", "Gestión pasaportes", "Gestión de pasaportes");

        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Economia
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Economia")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForEconomia(): array
    {
        $functionalities = array();

        //Economia
        $functionalities[] = Functionality::createFunctionality("MODULE_ECONOMIA", "ROLE_HOME_ECONOMIA", "Portada de Economía", "Portada de Economía");
        $functionalities[] = Functionality::createFunctionality("MODULE_ECONOMIA", "ROLE_GEST_CONCEPTO_GASTO", "Gestión de concepto de gasto", "Gestión de concepto de gasto");
        $functionalities[] = Functionality::createFunctionality("MODULE_ECONOMIA", "ROLE_GEST_OBSEQUIOS", "Gestión de obsequios", "Gestión de obsequios");

        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Evaluacion
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_ESTRUCTURA", "Módulo de Evaluacion")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForEvaluacion(): array
    {
        $functionalities = array();

        //Economia
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_HOME_EVALUACION", "Portada de Evaluación", "Portada de Evaluación");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_ESTADO_SOLICITUD", "Gestión de estados de solicitud", "Gestión de estados de solicitud");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_ESTADO_APLAZAMIENTO", "Gestión de estados de aplazamiento", "Gestión de estados de solicitud");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_CONVOCATORIA", "Gestión de convocatorias", "Gestión de convocatorias");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_SOLICITUD", "Gestión de solicitudes", "Gestión de solicitudes");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_PLAN_ANUAL_EVALUACION", "Gestión de planes anuales de evaluación", "Gestión de planes anuales de evaluación");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_INFORME_AUTOEVALUACION", "Gestión de solicitudes de autoevaluaciön", "Gestión de solicitudes de autoevaluaciön");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_PLAN_TRABAJO", "Gestión de solicitudes de planes de trabajo", "Gestión de solicitudes de planes de trabajo");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_ROL_COMISION", "Gestión de roles para comisión de evaluación", "Gestión de roles para comisión de evaluación");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_COMISION_EVALUADORA", "Gestión de comisiones evaluadoras", "Gestión de comisiones evaluadoras");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_GEST_APLAZAMIENTO", "Gestión solicitudes de aplazamiento", "Gestión solicitudes de aplazamiento");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION", "Gestión categorías de acreditación ies", "Gestión solicitudes de aplazamiento ies");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION_PREGRADO", "Gestión categorías de acreditación pregrado", "Gestión solicitudes de aplazamiento pregrado");
        $functionalities[] = Functionality::createFunctionality("MODULE_EVALUACION", "ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION_POSGRADO", "Gestión categorías de acreditación posgrado", "Gestión solicitudes de aplazamiento posgrado");

        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para Informatización
     *
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForInformatizacion(): array
    {
        $functionalities = array();

        //Informatización
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_HOME_INFORMATIZACION", "Portada de Indormatización", "Portada de Informatización");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_CENTRO_DATO_VIRTUAL", "Gestión de centros de datos virtuales", "Gestión de centros de datos virtuales");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_ENLACE_CONECTIVIDAD", "Gestión de enlaces de conectividad", "Gestión de enlaces de conectividad");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_MARCA", "Gestión de marcas", "Gestión de marcas");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_MODELO", "Gestión de modelos", "Gestión de modelos");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_PUBLICO_OBJETIVO", "Gestión de público objetivo", "Gestión de público objetivo");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_SERVICIO", "Gestión de servicios", "Gestión de servicios");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_SISTEMA_OPERATIVO", "Gestión de sistemas operativos", "Gestión de sistemas operativos");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_TIPO_CONECTIVIDAD", "Gestión de tipos de conectividad", "Gestión de tipos de conectividad");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_TIPO_SISTEMA", "Gestión de tipos de sistemas", "Gestión de tipos de sistemas");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_VISIBILIDAD", "Gestión de visibilidad", "Gestión de visibilidad");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_NAUTA_HOGAR", "Gestión de nauta hogar", "Gestión de nauta hogar");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_SISTEMA_INFORMATICO", "Gestión de sistemas informáticos", "Gestión de sistemas informáticos");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_LINEA_CELULAR", "Gestión de líneas celulares", "Gestión de líneas celulares");
        $functionalities[] = Functionality::createFunctionality("MODULE_INFORMATIZACION", "ROLE_GEST_TELEFONO_CELULAR", "Gestión de teléfonos celulares", "Gestión de teléfonos celulares");
        return $functionalities;
    }


    /**
     * Define una Lista de Funcionalidades para Reporte
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_REPORTE", "Módulo de Reporte")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForReporte(): array
    {
        $functionalities = array();

        //Reporte
        $functionalities[] = Functionality::createFunctionality("MODULE_REPORT", "ROLE_HOME_REPORT", "Portada de Reportes", "Portada de Reportes");
        $functionalities[] = Functionality::createFunctionality("MODULE_REPORT", "ROLE_CUSTOM_REP", "Reporte personalizado", "Reporte personalizado");


        return $functionalities;
    }


    /**
     * Define una Lista de Funcionalidades para Trazas
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_REPORTE", "Módulo de Trazas")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForTrazas(): array
    {
        $functionalities = array();

        //Traza
        $functionalities[] = Functionality::createFunctionality("MODULE_TRACE", "ROLE_HOME_TRAZAS", "Portada de Trazas", "Portada de Trazas");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRACE", "ROLE_ACCTION_TRZ", "Gestionar acciones de las trazas", "Gestionar acciones de las trazas");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRACE", "ROLE_OBJ_TRZ", "Gestionar objetos de las trazas", "Gestionar objetos de las trazas");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRACE", "ROLE_TYPTRZ", "Gestionar tipos de trazas", "Gestionar tipos de trazas");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRACE", "ROLE_TRAZ", "Gestionar trazas", "Gestionar trazas");


        return $functionalities;
    }

    /**
     * Define una Lista de Funcionalidades para RRHH
     *
     * For create one -> Functionality::createFunctionality("ROLE_MODULO_RRHH", "Módulo de RRHH")
     *
     * @return Functionality[]
     */
    public static function defineFunctionalitiesForRRHH(): array
    {
        $functionalities = array();

        //Reporte
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_HOME_RRHH", "Portada de RRHH", "Portada de RRHH");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_GEST_ESPECIALIDAD", "Gestión de especialidades", "Gestión de especialidades");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_GEST_CAT_DOC", "Gestión de categorías docentes especiales", "Gestión de categorías docentes especiales");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_REPORTE_AE2", "Gestión de modelo A2", "Gestión de modelo A2");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_REPORTE_AE2_CONSOLIDADO", "Reporte de modelo A2 IES", "Reporte de modelo A2 IES");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_REPORTE_AE3_CONSOLIDADO", "Reporte de modelo A3 IES", "Reporte de modelo A3 IES");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_REPORTE_AE2_MES", "Reporte de modelo A2 MES", "Reporte de modelo A2 MES");
        $functionalities[] = Functionality::createFunctionality("MODULE_RRHH", "ROLE_RRHH_REPORTE_AE3", "Gestión de modelo A3", "Gestión de modelo A3");


        return $functionalities;
    }


    //Roles -------------------------------------------------------------------------------------------

    /**
     * Define una Lista de Modulos
     *
     * For create one -> Role::createRole($roleKey, string $name, string $description = null)
     *                      ->addFunctionality('Funct1')
     *                      ->addFunctionalities('Funct2', 'Funct3')
     *
     * @return Module[]
     */
    public static function defineRoles(): array
    {
        $roles = array();

        $roles[] = Role::createRole('ROL_ADMIN', "Administrador de Seguridad", "Rol con permisos a las gestiones administrativas de seguridad")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_ADMIN")
            ->addFunctionality("ROLE_GEST_USER")
            ->addFunctionality("ROLE_GEST_FUNC")
            ->addFunctionality("ROLE_GEST_MODULE")
            ->addFunctionality("ROLE_GEST_ROLES");

        $roles[] = Role::createRole('ROL_STRUCT', "Administrador de Estructura y Composición", "Rol con permisos a todas las funcionalidades de estructura y composición")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_STRUCT")
            ->addFunctionality("ROLE_GEST_CATGEST")
            ->addFunctionality("ROLE_GEST_CATRESP")
//            ->addFunctionality("ROLE_GEST_ENTITY")
            ->addFunctionality("ROLE_GEST_ESTRUCT")
            ->addFunctionality("ROLE_GEST_MUNICP")
            ->addFunctionality("ROLE_GEST_PROVIN")
            ->addFunctionality("ROLE_GEST_RESPON")
            ->addFunctionality("ROLE_GEST_ENTITY")
            ->addFunctionality("ROLE_GEST_TYPEESTR");

        $roles[] = Role::createRole('ROL_PERSON', "Administrador de Personal", "Rol con permisos a todas las funcionalidades de personal")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_PERSONAL")
            ->addFunctionality("ROLE_GEST_CARRERA")
            ->addFunctionality("ROLE_GEST_CATDOC")
            ->addFunctionality("ROLE_GEST_CATINVST")
            ->addFunctionality("ROLE_GEST_CLAPER")
            ->addFunctionality("ROLE_GEST_GRADAKAD")
            ->addFunctionality("ROLE_GEST_NIVESC")
            ->addFunctionality("ROLE_GEST_ORGANZ")
            ->addFunctionality("ROLE_GEST_PERSON")
            ->addFunctionality("ROLE_GEST_PROF")
            ->addFunctionality("ROLE_GEST_RESPONS")
            ->addFunctionality("ROLE_GEST_TYPORG");


        $roles[] = Role::createRole('ROL_REPORT', "Visualizador de Reportes", "Rol con permisos a todos los reportes")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_REPORT")
            ->addFunctionality("ROLE_ESTPL_REP")
            ->addFunctionality("ROLE_MAPIND_REP")
            ->addFunctionality("ROLE_PROGRAM_REP");

        $roles[] = Role::createRole('ROL_TRAZ', "Administrador de Trazas", "Rol con permiso a las trazas del sistema")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_ACCTION")
            ->addFunctionality("ROLE_OBJ_TRZ")
            ->addFunctionality("ROLE_TYPTRZ")
            ->addFunctionality("ROLE_HOME_TRAZAS");


        $roles[] = Role::createRole('ROL_INSTIT', "Administrador de Instituciones", "Rol con permiso a la gestion de Instituciones")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_INSTIT")
            ->addFunctionality("ROLE_GEST_TIPINST")
            ->addFunctionality("ROLE_GEST_CATACRED");


        $roles[] = Role::createRole('ROL_PREGRADO', "Administrador de Pregrado", "Rol con permiso a la gestion de Pregrado")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_PREGR")
            ->addFunctionality("ROLE_GEST_CURACAD")
            ->addFunctionality("ROLE_GEST_ESTPROGACAD")
            ->addFunctionality("ROLE_GEST_TIPOPROGACAD")
            ->addFunctionality("ROLE_GEST_TIPOORG")
            ->addFunctionality("ROLE_GEST_COMNACION")
            ->addFunctionality("ROLE_GEST_ORGDEMAND")
            ->addFunctionality("ROLE_GEST_DOCS")
            ->addFunctionality("ROLE_GEST_PLANEST")
            ->addFunctionality("ROLE_GEST_SOLPROG")
            ->addFunctionality("ROLE_GEST_PROGAPROB")
            ->addFunctionality("ROLE_GEST_PROGEXTINT")
            ->addFunctionality("ROLE_GEST_TIP_SOLICITUD_POST")
            ->addFunctionality("ROLE_GEST_OACE")
            ->addFunctionality("ROLE_PREGRADO_REPORTE_PROG_APROBADOS")
            ->addFunctionality("ROLE_PREGRADO_REPORTE_PROG_POR_UNIVERSIDAD")
            ->addFunctionality("ROLE_PREGRADO_REPORTE_UNIVERSIDAD_POR_PROG");


        $roles[] = Role::createRole('ROL_POSGRADO', "Administrador de Posgrado", "Rol con permiso a la gestion de Posgrado")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_POSTG")
            ->addFunctionality("ROLE_GEST_SOLPROGRAM")
            ->addFunctionality("ROLE_GEST_GRADO_CIENTIFICO")
            ->addFunctionality("ROLE_GEST_FORMACION_ACADEMICA")
            ->addFunctionality("ROLE_GEST_COPEP")
            ->addFunctionality("ROLE_GEST_COMISION")
            ->addFunctionality("ROLE_GEST_TIPPROGRAM")
            ->addFunctionality("ROLE_GEST_RAMCIENC")
            ->addFunctionality("ROLE_GEST_CENTRO_AUTORIZADO")
            ->addFunctionality("ROLE_GEST_ROLESPOS")
            ->addFunctionality("ROLE_GEST_REPORTE_FORMACION_ACADEMICA")
            ->addFunctionality("ROLE_GEST_REPORTE_GRADO_CIENTIFICO")
            ->addFunctionality("ROLE_GEST_FORMACION_DOCTORES");


        $roles[] = Role::createRole('ROL_CONVENIO', "Administrador de Convenio", "Rol con permiso a la gestion de Convenio")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_CONVENIO")
            ->addFunctionality("ROLE_GEST_CONVENIO")
            ->addFunctionality("ROLE_GEST_TIPO")
            ->addFunctionality("ROLE_GEST_INST_EXTRANJERA")
            ->addFunctionality("ROLE_GEST_MODALIDAD")
            ->addFunctionality("ROLE_GEST_REGION");


        $roles[] = Role::createRole('ROL_TRAMITES', "Administrador de Trámites", "Rol con permiso a la gestion de Trámites")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_TRAMITES")
            ->addFunctionality("ROLE_GEST_PLAN_MISION")
            ->addFunctionality("ROLE_GEST_PLAN_MISION_DETALLES")
            ->addFunctionality("ROLE_GEST_TIPO_PASAPORTE")
            ->addFunctionality("ROLE_GEST_CONCEPTO_SALIDA")
            ->addFunctionality("ROLE_GEST_FICHA_SALIDA")
            ->addFunctionality("ROLE_GEST_DOCUMENTO_SALIDA")
            ->addFunctionality("ROLE_GEST_TRAMITE");


        $roles[] = Role::createRole('ROL_ECONOMIA', "Administrador de Economía", "Rol con permiso a la gestion de Economía", "")
            ->addFunctionality("ROLE_HOME_ECONOMIA")
            ->addFunctionality("ROLE_GEST_CONCEPTO_GASTO")
            ->addFunctionality("ROLE_GEST_OBSEQUIOS");


        $roles[] = Role::createRole('ROL_EVALUACION', "Administrador de Evaluación", "Rol con permiso a la gestion de Evaluación", "")
            ->addFunctionality("ROL_EVALUACION")
            ->addFunctionality("ROLE_GEST_CONVOCATORIA")
            ->addFunctionality("ROLE_GEST_SOLICITUD")
            ->addFunctionality("ROLE_GEST_PLAN_ANUAL_EVALUACION")
            ->addFunctionality("ROLE_GEST_INFORME_AUTOEVALUACION")
            ->addFunctionality("ROLE_GEST_PLAN_TRABAJO")
            ->addFunctionality("ROLE_GEST_ESTADO_SOLICITUD")
            ->addFunctionality("ROLE_GEST_ESTADO_APLAZAMIENTO")
            ->addFunctionality("ROLE_GEST_COMISION_EVALUADORA")
            ->addFunctionality("ROLE_GEST_APLAZAMIENTO")
            ->addFunctionality("ROLE_GEST_ROL_COMISION")
            ->addFunctionality("ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION")
            ->addFunctionality("ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION_PREGRADO")
            ->addFunctionality("ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION_POSGRADO");


        $roles[] = Role::createRole('ROL_INFORMATIZACION', "Administrador de Informatización", "Rol con permiso a la gestion de Informatización", "")
            ->addFunctionality("ROL_INFORMATIZACION")
            ->addFunctionality("ROLE_HOME_INFORMATIZACION")
            ->addFunctionality("ROLE_GEST_CENTRO_DATO_VIRTUAL")
            ->addFunctionality("ROLE_GEST_ENLACE_CONECTIVIDAD")
            ->addFunctionality("ROLE_GEST_MARCA")
            ->addFunctionality("ROLE_GEST_MODELO")
            ->addFunctionality("ROLE_GEST_PUBLICO_OBJETIVO")
            ->addFunctionality("ROLE_GEST_SERVICIO")
            ->addFunctionality("ROLE_GEST_SISTEMA_OPERATIVO")
            ->addFunctionality("ROLE_GEST_TIPO_CONECTIVIDAD")
            ->addFunctionality("ROLE_GEST_TIPO_SISTEMA")
            ->addFunctionality("ROLE_GEST_NAUTA_HOGAR")
            ->addFunctionality("ROLE_GEST_SISTEMA_INFORMATICO")
            ->addFunctionality("ROLE_GEST_LINEA_CELULAR")
            ->addFunctionality("ROLE_GEST_TELEFONO_CELULAR")
            ->addFunctionality("ROLE_GEST_VISIBILIDAD");

        $roles[] = Role::createRole('ROL_RRHH', "Administrador de RRHH", "Rol con permiso a la gestion de RRHH")
            ->addFunctionality("ROL_RRHH")
            ->addFunctionality("ROLE_HOME_RRHH")
            ->addFunctionality("ROLE_RRHH_REPORTE_AE2")
            ->addFunctionality("ROLE_RRHH_REPORTE_AE3")
            ->addFunctionality("ROLE_RRHH_GEST_CAT_DOC")
            ->addFunctionality("ROLE_RRHH_REPORTE_AE2_CONSOLIDADO")
            ->addFunctionality("ROLE_RRHH_REPORTE_AE3_CONSOLIDADO")
            ->addFunctionality("ROLE_RRHH_REPORTE_AE2_MES")
            ->addFunctionality("ROLE_RRHH_GEST_ESPECIALIDAD");
        return $roles;
    }
}