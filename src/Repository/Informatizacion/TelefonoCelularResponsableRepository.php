<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\TelefonoCelularResponsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TelefonoCelularResponsable>
 *
 * @method TelefonoCelularResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelefonoCelularResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelefonoCelularResponsable[]    findAll()
 * @method TelefonoCelularResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelefonoCelularResponsableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelefonoCelularResponsable::class);
    }

    public function add(TelefonoCelularResponsable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TelefonoCelularResponsable $entity, bool $flush = true): TelefonoCelularResponsable
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TelefonoCelularResponsable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
