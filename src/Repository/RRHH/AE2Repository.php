<?php

namespace App\Repository\RRHH;

use App\Entity\RRHH\AE2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AE2>
 *
 * @method AE2|null find($id, $lockMode = null, $lockVersion = null)
 * @method AE2|null findOneBy(array $criteria, array $orderBy = null)
 * @method AE2[]    findAll()
 * @method AE2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AE2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AE2::class);
    }

    public function add(AE2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(AE2 $entity, bool $flush = true): AE2
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(AE2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDistinctEntidades(?int $entidadId = null, ?int $mes = null, ?int $anno = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('DISTINCT e.id, e.nombre')
            ->join('a.entidad', 'e');

        if ($entidadId !== null) {
            $qb->andWhere('e.id = :entidadId')
                ->setParameter('entidadId', $entidadId);
        }

        if ($mes !== null) {
            $qb->andWhere('a.mes = :mes')
                ->setParameter('mes', $mes);
        }

        if ($anno !== null) {
            $qb->andWhere('a.anno = :anno')
                ->setParameter('anno', $anno);
        }

        return $qb->orderBy('e.nombre', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }


}
