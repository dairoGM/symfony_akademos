<?php


namespace App\Command\Install;


class Role
{
    /**
     * Key que define el rol
     */
    private $roleKey;

     /**
     * Nombre del Modulo
     */
    private $name;

    /**
     * Descripcion de la Modulo
     */
    private $description;

    /**
     * Funcionalidades a las que el rol tiene acceso
     *
     * @var array
     */
    private $functionalitiesKey = array();
    

    public static function createRole($roleKey, string $name, string $description = null)
    {
        $role = new Role();
        $role->roleKey = $roleKey;
        $role->name = $name;
        $role->description = $description;

        return $role;
    }

    public function addFunctionality($functionalityKey) : Role
    {
        if(!in_array($functionalityKey, $this->functionalitiesKey))
        {
            $this->functionalitiesKey[] = $functionalityKey;
        }    

        return $this;
    }

    public function addFunctionalities(...$functionalitiesKey) : Role
    {
        foreach($functionalitiesKey as $functionalityKey)
        {
            $this->addFunctionality($functionalityKey);
        }

        return $this;
    }

    /**
     * Get descripcion de la Funcionalidad
     */ 
    public function getDescription()
    {
        return $this->description;
    }
   
    /**
     * Get nombre del Modulo
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get key que define el rol
     */ 
    public function getRoleKey()
    {
        return $this->roleKey;
    }

    /**
     * Set key que define el rol
     *
     * @return  self
     */ 
    public function setRoleKey($roleKey)
    {
        $this->roleKey = $roleKey;

        return $this;
    }

    /**
     * Get funcionalidades a las que el rol tiene acceso
     *
     * @return  array
     */ 
    public function getFunctionalitiesKey()
    {
        return $this->functionalitiesKey;
    }
}