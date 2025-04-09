<?php

namespace App\Repository\Economia;

use App\Entity\Economia\Obsequio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Obsequio>
 *
 * @method Obsequio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Obsequio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Obsequio[]    findAll()
 * @method Obsequio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObsequioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Obsequio::class);
    }

    public function add(Obsequio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Obsequio $entity, bool $flush = true): Obsequio
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Obsequio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
