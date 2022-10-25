<?php

namespace App\Services\Install;

use App\Command\Install\InstallRoleInterface;
use App\Command\Install\Role;
use App\Entity\Security\Rol;
use App\Repository\Security\FuncionalidadRepository;
use App\Repository\Security\RolRepository;

class InstallRoleService implements InstallRoleInterface
{

    private RolRepository $roleRepository;

    private FuncionalidadRepository $funcionalidadRepository;

    function __construct(RolRepository $roleRepository, FuncionalidadRepository $funcionalidadRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->funcionalidadRepository = $funcionalidadRepository;
    }
    
     /**
     * Define una Lista de Roles
     * 
     * For create one -> Role::createRole($roleKey, string $name, string $description = null)
     *
     * @return Role[]
     */
    function defineRoles() : array
    {
        return InstallConfig::defineRoles();
    }

     /**
     * Devuelve si una funcionalidad existe dada su roleKey
     *
     * @return bool
     */
    function getExistRole($roleKey) : bool
    {
        return $this->roleRepository->existRole($roleKey);
    }

   
     /**
     * Crea una Funcionalidad 
     * 
     * @param Role $functionality
     */
    function create(Role $role) : void
    {
        $roleEntity = new Rol();

        $roleEntity->setRoleKey($role->getRoleKey());
        $roleEntity->setNombre($role->getName());
        $roleEntity->setDescripcion($role->getDescription() ?? $role->getDescription());

        $rolFunctionalidades = $this->getFunctionalities($role->getFunctionalitiesKey());
        $roleEntity->setFuncionalidades($rolFunctionalidades);

        $this->roleRepository->add($roleEntity, true);        
    }

    /**
     * Actualiza un rol 
     * 
     * @param Role $rol
     */
    function update(Role $role) : void
    {
        $roleEntity = $this->roleRepository->findByRoleKey($role->getRoleKey()); 

        $roleEntity->setRoleKey($role->getRoleKey());
        $roleEntity->setNombre($role->getName());
        $roleEntity->setDescripcion($role->getDescription() ?? $role->getDescription());

        $rolFunctionalidades = $this->getFunctionalities($role->getFunctionalitiesKey());
        $roleEntity->setFuncionalidades($rolFunctionalidades);

        $this->roleRepository->edit($roleEntity, true);     
    }   

    private function getFunctionalities($functionalitiesKey) : array
    {
        $rolFunctionalidades = array();

        foreach($functionalitiesKey as $functionalityKey)
        {
            $funcionalidad = $this->funcionalidadRepository->findByRoleKey($functionalityKey);
            if(null != $funcionalidad)
            {
                $rolFunctionalidades[] = $funcionalidad;
            }
        }

        return $rolFunctionalidades;
    }
}