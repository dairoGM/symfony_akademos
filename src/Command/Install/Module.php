<?php


namespace App\Command\Install;


class Module
{
    /**
     * Key que define el modulo
     */
    private $moduleKey;

     /**
     * Nombre del Modulo
     */
    private $name;

    /**
     * Descripcion de la Modulo
     */
    private $description;
    

    public static function createModule($moduleKey, string $name, string $description = null)
    {
        $module = new Module();
        $module->moduleKey = $moduleKey;
        $module->name = $name;
        $module->description = $description;

        return $module;
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

    /**
     * Get nombre del Modulo
     */ 
    public function getName()
    {
        return $this->name;
    }
}