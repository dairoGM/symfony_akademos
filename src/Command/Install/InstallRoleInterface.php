<?php

namespace App\Command\Install;

/** 
 * Define Interfaz de Instalación de Funcionalidades
 */
interface InstallRoleInterface
{

    /**
     * Define una Lista de Roles
     * 
     * For create one -> Role::createRole($roleKey, string $name, string $description = null)
     *
     * @return Role[]
     */
    function defineRoles() : array;

    /**
     * Devuelve si una funcionalidad existe dada su roleKey
     *
     * @return bool
     */
    function getExistRole($roleKey) : bool;
   
    /**
     * Crea una Funcionalidad 
     * 
     * @param Role $functionality
     */
    function create(Role $role) : void;   

    /**
     * Actualiza un rol 
     * 
     * @param Role $rol
     */
    function update(Role $role) : void;
}