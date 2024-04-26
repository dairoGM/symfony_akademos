<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\PlanMision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanMision>
 *
 * @method PlanMision|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanMision|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanMision[]    findAll()
 * @method PlanMision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanMisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanMision::class);
    }

    public function add(PlanMision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PlanMision $entity, bool $flush = true): PlanMision
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PlanMision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
