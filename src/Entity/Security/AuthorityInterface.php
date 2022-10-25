<?php

namespace App\Entity\Security;

/** 
 * Define Interfaz de Seguridad
 */
interface AuthorityInterface
{
    /**
     * Devuelve una array con los authorities (permisos o roles)
     *
     * @return string[]
     */
    function getAuthorities() : array;

    /**
     * Devuelve si la Authority esta habilitada
     *
     * @return boolean
     */
    function authorityEnabled() : bool;
}