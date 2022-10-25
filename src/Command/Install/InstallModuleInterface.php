<?php

namespace App\Command\Install;

/** 
 * Define Interfaz de Instalación de Funcionalidades
 */
interface InstallModuleInterface
{

    /**
     * Define los modulos
     * 
     * For create one ->  Module:createModule($moduleKey, string $name, string $description = null)
     *
     * @return Module[]
     */
    function defineModules() : array;

    /**
     * Devuelve si un modulo  existe dada su moduleKey
     *
     * @return bool
     */
    function getExistModule($moduleKey) : bool;
    
    /**
     * Crea un modulo 
     * 
     * @param Module $module
     */
    function create(Module $module) : void;

    /**
     * Actualiza un modulo 
     * 
     * @param Module $module
     */
    function update(Module $module) : void;
}