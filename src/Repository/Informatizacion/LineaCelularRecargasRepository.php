<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\LineaCelularRecargas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LineaCelularRecargas>
 *
 * @method LineaCelularRecargas|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineaCelularRecargas|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineaCelularRecargas[]    findAll()
 * @method LineaCelularRecargas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaCelularRecargasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineaCelularRecargas::class);
    }

    public function add(LineaCelularRecargas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(LineaCelularRecargas $entity, bool $flush = true): LineaCelularRecargas
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(LineaCelularRecargas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
