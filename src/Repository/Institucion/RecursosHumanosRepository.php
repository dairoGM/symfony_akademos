<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\RecursosHumanos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecursosHumanos>
 *
 * @method RecursosHumanos|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecursosHumanos|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecursosHumanos[]    findAll()
 * @method RecursosHumanos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecursosHumanosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecursosHumanos::class);
    }

    public function add(RecursosHumanos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RecursosHumanos $entity, bool $flush = true): RecursosHumanos
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RecursosHumanos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
