<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\TipoComision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoComision>
 *
 * @method TipoComision|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoComision|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoComision[]    findAll()
 * @method TipoComision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoComisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoComision::class);
    }

    public function add(TipoComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoComision $entity, bool $flush = true): TipoComision
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
