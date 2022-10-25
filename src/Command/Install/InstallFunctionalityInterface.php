<?php

namespace App\Command\Install;

/** 
 * Define Interfaz de Instalación de Funcionalidades
 */
interface InstallFunctionalityInterface
{

    /**
     * Define una Lista de Funcionalidades
     * 
     * For create one -> Functionality::createFunctionality("ROLE_ADMIN_USER", "Administrar usuarios")
     *
     * @return Functionality[]
     */
    function defineFunctionalities() : array;

    /**
     * Devuelve si una funcionalidad existe dada su roleKey
     *
     * @return bool
     */
    function getExistFuncionality($roleKey) : bool;

    /**
     * Devuelve los RoleKeys de las funcionalidades Instaladas
     *
     * @return string[]
     */
    function getAllRoleKeyInstaledFuncionalities() : array;

    /**
     * Crea una Funcionalidad 
     * 
     * @param Functionality $functionality
     */
    function create(Functionality $functionality) : void;

    /**
     * Actualiza una Funcionalidad 
     * 
     * @param Functionality $functionality
     */
    function update(Functionality $functionality) : void;

    /**
     * Elimina una Funcionalidad 
     * 
     * @param string $roleKey de la funcionalidad
     */
    function delete($roleKey) : void;

    /**
     * Deshabilita una Funcionalidad 
     * 
     * @param string $roleKey de la funcionalidad
     */
    function disable($roleKey) : void;
}