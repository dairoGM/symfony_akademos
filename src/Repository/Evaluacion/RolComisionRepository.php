<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\RolComision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RolComision>
 *
 * @method RolComision|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolComision|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolComision[]    findAll()
 * @method RolComision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolComisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolComision::class);
    }

    public function add(RolComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RolComision $entity, bool $flush = true): RolComision
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RolComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
