<?php

namespace App\Services\Install;

use App\Entity\Security\Funcionalidad;
use App\Repository\Security\FuncionalidadRepository;
use App\Command\Install\Functionality;
use App\Command\Install\InstallFunctionalityInterface;
use App\Repository\Security\ModuloRepository;

class InstallFuntionalityService implements InstallFunctionalityInterface
{

    private FuncionalidadRepository $funcionalidadRepository;

    private ModuloRepository $moduloRepository;

    function __construct(FuncionalidadRepository $funcionalidadRepository, ModuloRepository $moduloRepository)
    {
        $this->funcionalidadRepository = $funcionalidadRepository;
        $this->moduloRepository = $moduloRepository;
    }

    /**
     * Define una Lista de Funcionalidades
     *
     * For create one -> Functionality::createFunctionality("ROLE_ADMIN_USER", "Administrar usuarios")
     *
     * @return Functionality[]
     */
    function defineFunctionalities(): array
    {
        $functionalities = array();

        //Comunes
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesComunes());

        //Administración ...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForAdministracion());

        //Personal ...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForPersonal());

        //Estructura...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForEstructura());

        //Reporte
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForReporte());

        //Trazas...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForTrazas());

        //Instituciones...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForInstituciones());

        //Pregrado...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForPregrado());

        //Postgrado...ok
        $functionalities = array_merge($functionalities, InstallConfig::defineFunctionalitiesForPostgrado());

        return $functionalities;
    }

    /**
     * Devuelve una funcionalidad existe dada su roleKey
     *
     * @return bool
     */
    function getExistFuncionality($roleKey): bool
    {
        return $this->funcionalidadRepository->existFuncionalidad($roleKey);
    }

    /**
     * Devuelve los RoleKeys de las funcionalidades Instaladas
     *
     * @return string[]
     */
    function getAllRoleKeyInstaledFuncionalities(): array
    {
        $currentInstaled = $this->funcionalidadRepository->findAll();

        $roleKeys = array();
        foreach ($currentInstaled as $key => $functionality) {
            $roleKeys[] = $functionality->getRoleKey();
        }

        return $roleKeys;
    }

    /**
     * Crea una Funcionalidad
     *
     * @param Functionality $functionality
     */
    function create(Functionality $functionality): void
    {
        $functionalityEntity = new Funcionalidad();

        $functionalityEntity->setModulo($this->getModule($functionality->getModuleKey()));
        $functionalityEntity->setRoleKey($functionality->getRoleKey());
        $functionalityEntity->setNombre($functionality->getName());
        $functionalityEntity->setDescripcion($functionality->getDescription() ?? $functionalityEntity->getDescripcion());

        $this->funcionalidadRepository->add($functionalityEntity, true);
    }

    /**
     * Actualiza una Funcionalidad
     *
     * @param Functionality $functionality
     */
    function update(Functionality $functionality): void
    {
        $functionalityEntity = $this->funcionalidadRepository->findByRoleKey($functionality->getRoleKey());

        $functionalityEntity->setModulo($this->getModule($functionality->getModuleKey()));
        $functionalityEntity->setRoleKey($functionality->getRoleKey());
        $functionalityEntity->setNombre($functionality->getName());
        $functionalityEntity->setDescripcion($functionality->getDescription() ?? $functionalityEntity->getDescripcion());

        $this->funcionalidadRepository->edit($functionalityEntity, true);
    }

    /**
     * Elimina una Funcionalidad
     *
     * @param string $roleKey de la funcionalidad
     */
    function delete($roleKey): void
    {
        $functionalityEntity = $this->funcionalidadRepository->findByRoleKey($roleKey);

        $this->funcionalidadRepository->remove($functionalityEntity, true);
    }

    /**
     * Deshabilita una Funcionalidad
     *
     * @param string $roleKey de la funcionalidad
     */
    function disable($roleKey): void
    {
        $functionalityEntity = $this->funcionalidadRepository->findByRoleKey($roleKey);

        $functionalityEntity->setActivo(false);

        $this->funcionalidadRepository->edit($functionalityEntity, true);
    }

    /**
     * Elimina/Desinstala/Deshabilita, las Funcionalidades  Definidas defineFunctionalities()
     *
     *  Action: define la accion de instalacion
     *  disable-> Indica que se deben Deshabilitar las funcionalidades
     *  delete -> Indica que se debe Eliminar las funcionalidades
     *
     * @param Functionality[] Funcionalidades Definidas en defineFunctionalities()
     * @param [type] $action
     * @return int Cantidad de registros afectados
     */
    function uninstallAll($functionalities, $action): array
    {
        $roleKeys = array();
        foreach ($functionalities as $key => $functionality) {
            $roleKeys[] = $functionality->getRoleKey();
        }

        $unistaled = array();
        $currentInstaled = $this->funcionalidadRepository->findAll();

        foreach ($currentInstaled as &$functionalityEntity) {
            $roleKey = $functionalityEntity->getRoleKey();

            if (!in_array($roleKey, $roleKeys)) {
                $functionalty = Functionality::createFunctionality(
                    $functionalityEntity->getRoleKey(),
                    $functionalityEntity->getNombre(),
                    $functionalityEntity->getDescripcion()
                );

                if ($action == 'delete') {
                    $this->funcionalidadRepository->remove($functionalityEntity, true);
                    $unistaled[] = $functionalty;
                } else if ($action == 'disable') {
                    $functionalityEntity->setActivo(false);
                    $this->funcionalidadRepository->edit($functionalityEntity);
                    $unistaled[] = $functionalty;
                }
            }
        }

        return $unistaled;
    }

    /**
     * Devuelve una funcionalidad existe dada su roleKey
     *
     * @return bool
     */
    function getExistModule($moduleKey): bool
    {
        return $this->moduloRepository->existModule($moduleKey);
    }

    private function getModule($moduleKey)
    {
        return $this->moduloRepository->findByModuleKey($moduleKey);
    }
}