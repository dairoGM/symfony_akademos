<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\LineaCelularResponsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LineaCelularResponsable>
 *
 * @method LineaCelularResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineaCelularResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineaCelularResponsable[]    findAll()
 * @method LineaCelularResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaCelularResopnsableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineaCelularResponsable::class);
    }

    public function add(LineaCelularResponsable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(LineaCelularResponsable $entity, bool $flush = true): LineaCelularResponsable
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(LineaCelularResponsable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
