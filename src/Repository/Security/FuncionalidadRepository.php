<?php

namespace App\Repository\Security;

use App\Entity\Security\Funcionalidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Funcionalidad>
 *
 * @method Funcionalidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Funcionalidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Funcionalidad[]    findAll()
 * @method Funcionalidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuncionalidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Funcionalidad::class);
    }

    public function add(Funcionalidad $entity, bool $flush = false): Funcionalidad
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function edit(Funcionalidad $entity, bool $flush = true): Funcionalidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Funcionalidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByRoleKey($roleKey) : ?Funcionalidad
    {
        $criteria = array();
        $criteria['roleKey'] = $roleKey;

        return $this->findOneBy($criteria);
    }

    public function existFuncionalidad($roleKey) : bool
    {
        $criteria = array();
        $criteria['roleKey'] = $roleKey;

        return $this->count($criteria) > 0;
    }
}
