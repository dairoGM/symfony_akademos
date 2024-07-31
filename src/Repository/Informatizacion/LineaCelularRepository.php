<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\LineaCelular;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LineaCelular>
 *
 * @method LineaCelular|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineaCelular|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineaCelular[]    findAll()
 * @method LineaCelular[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaCelularRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineaCelular::class);
    }

    public function add(LineaCelular $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(LineaCelular $entity, bool $flush = true): LineaCelular
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(LineaCelular $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
