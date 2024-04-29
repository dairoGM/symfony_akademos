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
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_PROGRAMAPROB", "Gestionar programas aprobados", "Gestionar programas aprobados");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_COPEP", "Gestionar copep", "Gestionar copep");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_COMISION", "Gestionar comision", "Gestionar comision");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_TIPPROGRAM", "Gestionar tipo de programa", "Gestionar tipo de programa");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_RAMCIENC", "Gestionar rama de la ciencia", "Gestionar rama de la ciencia");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_ROLESPOS ", "Gestionar roles", "Gestionar roles");
        $functionalities[] = Functionality::createFunctionality("MODULE_POSTG", "ROLE_GEST_TIP_SOLICITUD_POST ", "Gestionar tipos de solicitudes", "Gestionar tipos de solicitudes");

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
        $functionalities[] = Functionality::createFunctionality("MODULE_CONVENIO", "ROLE_GEST_CONVENIO", "Gestión de convenios", "Gestión de convenios");

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
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_INST_EXTRANJERA", "Gestión de institución extranjera", "Gestión de institución extranjera");
        $functionalities[] = Functionality::createFunctionality("MODULE_TRAMITE", "ROLE_GEST_CONCEPTO_SALIDA", "Gestión de conceptos de salida", "Gestión de conceptos de salida");

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
//            ->addFunctionality("ROLE_GEST_TYPENTY")
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
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_REPORT")
            ->addFunctionality("ROLE_ESTPL_REP")
            ->addFunctionality("ROLE_MAPIND_REP")
            ->addFunctionality("ROLE_PROGRAM_REP");

        $roles[] = Role::createRole('ROL_TRAZ', "Administrador de Trazas", "Rol con permiso a las trazas del sistema")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_ACCTION")
            ->addFunctionality("ROLE_OBJ_TRZ")
            ->addFunctionality("ROLE_TYPTRZ")
            ->addFunctionality("ROLE_HOME_TRAZAS");


        $roles[] = Role::createRole('ROL_INSTIT', "Administrador de Instituciones", "Rol con permiso a la gestion de Instituciones")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_INSTIT")
            ->addFunctionality("ROLE_GEST_TIPINST")
            ->addFunctionality("ROLE_GEST_CATACRED");


        $roles[] = Role::createRole('ROL_PREGRADO', "Administrador de Pregrado", "Rol con permiso a la gestion de Pregrado")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
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
            ->addFunctionality("ROLE_GEST_OACE");


        $roles[] = Role::createRole('ROL_POSGRADO', "Administrador de Posgrado", "Rol con permiso a la gestion de Posgrado")
            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_POSTG")
            ->addFunctionality("ROLE_GEST_SOLPROGRAM")
            ->addFunctionality("ROLE_GEST_PROGRAMAPROB")
            ->addFunctionality("ROLE_GEST_COPEP")
            ->addFunctionality("ROLE_GEST_COMISION")
            ->addFunctionality("ROLE_GEST_TIPPROGRAM")
            ->addFunctionality("ROLE_GEST_RAMCIENC")
            ->addFunctionality("ROLE_GEST_ROLESPOS");


        $roles[] = Role::createRole('ROL_CONVENIO', "Administrador de Convenio", "Rol con permiso a la gestion de Convenio")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_CONVENIO")
            ->addFunctionality("ROLE_GEST_CONVENIO")
            ->addFunctionality("ROLE_GEST_TIPO")
            ->addFunctionality("ROLE_GEST_MODALIDAD");


        $roles[] = Role::createRole('ROL_TRAMITES', "Administrador de Trámites", "Rol con permiso a la gestion de Trámites")
//            ->addFunctionality("ROLE_PORTADA_ADMIN")
            ->addFunctionality("ROLE_HOME_TRAMITES")
            ->addFunctionality("ROLE_GEST_PLAN_MISION")
            ->addFunctionality("ROLE_GEST_TIPO_PASAPORTE")
            ->addFunctionality("ROLE_GEST_INST_EXTRANJERA")
            ->addFunctionality("ROLE_GEST_CONCEPTO_SALIDA");
        return $roles;
    }
}