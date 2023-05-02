<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\PlanEstudioDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanEstudioDocumento>
 *
 * @method PlanEstudioDocumento|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanEstudioDocumento|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanEstudioDocumento[]    findAll()
 * @method PlanEstudioDocumento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanEstudioDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanEstudioDocumento::class);
    }

    public function add(PlanEstudioDocumento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PlanEstudioDocumento $entity, bool $flush = true): PlanEstudioDocumento
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PlanEstudioDocumento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
