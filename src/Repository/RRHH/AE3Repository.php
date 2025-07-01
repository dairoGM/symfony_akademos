<?php

namespace App\Repository\RRHH;

use App\Entity\RRHH\AE3;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AE3|null find($id, $lockMode = null, $lockVersion = null)
 * @method AE3|null findOneBy(array $criteria, array $orderBy = null)
 * @method AE3[]    findAll()
 * @method AE3[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AE3Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AE3::class);
    }

    public function add(AE3 $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function edit(AE3 $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(AE3 $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }



    public function findDistinctEntidades(?int $entidadId = null, ?int $mes = null, ?int $anno = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('DISTINCT e.id, e.nombre')
            ->join('a.entidad', 'e')
            ->orderBy('a.mes', 'asc');

        if ($anno !== null) {
            $qb->andWhere('a.anno = :anno')
                ->setParameter('anno', $anno);
        }

        return $qb->orderBy('e.nombre', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}