<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\CentroDatoVirtual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CentroDatoVirtual>
 *
 * @method CentroDatoVirtual|null find($id, $lockMode = null, $lockVersion = null)
 * @method CentroDatoVirtual|null findOneBy(array $criteria, array $orderBy = null)
 * @method CentroDatoVirtual[]    findAll()
 * @method CentroDatoVirtual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentroDatoVirtualRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CentroDatoVirtual::class);
    }

    public function add(CentroDatoVirtual $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CentroDatoVirtual $entity, bool $flush = true): CentroDatoVirtual
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CentroDatoVirtual $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
