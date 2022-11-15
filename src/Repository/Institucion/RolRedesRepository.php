<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\RolRedes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RolRedes>
 *
 * @method RolRedes|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolRedes|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolRedes[]    findAll()
 * @method RolRedes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolRedesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolRedes::class);
    }

    public function add(RolRedes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RolRedes $entity, bool $flush = true): RolRedes
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RolRedes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
