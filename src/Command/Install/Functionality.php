<?php


namespace App\Command\Install;


class Functionality
{
    /**
     * Define un permiso (authority) para la funcionalidad :  
     *    Ejemplo: ROLE_MNG_PERSONA  -> Para la Funcionalidad o Permiso de gestionar personas.
     * 
     * Deben empezar simpre con ROLE_  
     * No debe ser editado
     */
    private $roleKey;

     /**
     * Nombre de la Funcionalidad
     */
    private $name;

    /**
     * Descripcion de la Funcionalidad
     */
    private $description;

    /**
     * Modulo (moduleKey) al cual pertenece
     */
    private $moduleKey;

    public static function createFunctionality($moduleKey, string $roleKey, string $name, string $description = null)
    {
        $functionality = new Functionality();
        $functionality->moduleKey = $moduleKey;
        $functionality->roleKey = $roleKey;
        $functionality->name = $name;
        $functionality->description = $description;

        return $functionality;
    }

    /**
     * Get define un permiso (authority) para la funcionalidad :
     */ 
    public function getRoleKey()
    {
        return $this->roleKey;
    }

    /**
     * Get nombre de la Funcionalidad
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get descripcion de la Funcionalidad
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get modulo (moduleKey) al cual pertenece
     */ 
    public function getModuleKey()
    {
        return $this->moduleKey;
    }
}