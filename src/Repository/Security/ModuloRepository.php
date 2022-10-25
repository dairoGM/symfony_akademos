<?php

namespace App\Repository\Security;

use App\Entity\Security\Modulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Modulo>
 *
 * @method Modulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modulo[]    findAll()
 * @method Modulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modulo::class);
    }

    public function add(Modulo $entity, bool $flush = false): Modulo
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function edit(Modulo $entity, bool $flush = true): Modulo
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Modulo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByModuleKey($moduleKey) : ?Modulo
    {
        $criteria = array();
        $criteria['moduleKey'] = $moduleKey;

        return $this->findOneBy($criteria);
    }

    public function existModule($moduleKey) : bool
    {
        $criteria = array();
        $criteria['moduleKey'] = $moduleKey;

        return $this->count($criteria) > 0;
    }
}
