<?php

namespace App\Services\Install;

use App\Command\Install\InstallModuleInterface;
use App\Command\Install\Module;
use App\Entity\Security\Modulo;
use App\Repository\Security\ModuloRepository;

class InstallModuleService implements InstallModuleInterface
{

    private ModuloRepository $moduloRepository;

    function __construct(ModuloRepository $moduloRepository)
    {
        $this->moduloRepository = $moduloRepository;
    }
    
     /**
     * Define los modulos
     * 
     * For create one ->  Module:createModule($moduleKey, string $name, string $description = null)
     *
     * @return Module[]
     */
    function defineModules() : array
    {
        return InstallConfig::defineModules();
    }

    /**
     * Devuelve una funcionalidad existe dada su roleKey
     *
     * @return bool
     */
    function getExistModule($moduleKey) : bool
    {
        return $this->moduloRepository->existModule($moduleKey);
    }

   
     /**
     * Crea un modulo 
     * 
     * @param Module $module
     */
    function create(Module $module) : void
    {
        $moduleEntity = new Modulo();

        $moduleEntity->setModuleKey($module->getModuleKey());
        $moduleEntity->setNombre($module->getName());
        $moduleEntity->setDescripcion($module->getDescription() ?? $module->getDescription());

        $this->moduloRepository->add($moduleEntity, true);        
    }

    /**
     * Actualiza un modulo 
     * 
     * @param Module $module
     */
    function update(Module $module) : void
    {
        $moduleEntity = $this->moduloRepository->findByModuleKey($module->getModuleKey()); 

        $moduleEntity->setModuleKey($module->getModuleKey());
        $moduleEntity->setNombre($module->getName());
        $moduleEntity->setDescripcion($module->getDescription() ?? $module->getDescription());

        $this->moduloRepository->edit($moduleEntity, true);    
    }   
}