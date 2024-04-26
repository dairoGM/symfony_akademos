<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\PlanMisionDetalles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanMisionDetalles>
 *
 * @method PlanMisionDetalles|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanMisionDetalles|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanMisionDetalles[]    findAll()
 * @method PlanMisionDetalles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanMisionDetallesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanMisionDetalles::class);
    }

    public function add(PlanMisionDetalles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PlanMisionDetalles $entity, bool $flush = true): PlanMisionDetalles
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PlanMisionDetalles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
