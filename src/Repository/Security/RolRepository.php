<?php

namespace App\Repository\Security;

use App\Entity\Security\Rol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rol>
 *
 * @method Rol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rol[]    findAll()
 * @method Rol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rol::class);
    }

    public function add(Rol $entity, bool $flush = false): Rol
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function edit(Rol $entity, bool $flush = true): Rol
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Rol $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByRoleKey($roleKey) : ?Rol
    {
        $criteria = array();
        $criteria['roleKey'] = $roleKey;

        return $this->findOneBy($criteria);
    }

    public function existRole($roleKey) : bool
    {
        $criteria = array();
        $criteria['roleKey'] = $roleKey;

        return $this->count($criteria) > 0;
    }
}
