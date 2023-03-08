<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\Oace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Oace>
 *
 * @method Oace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oace[]    findAll()
 * @method Oace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oace::class);
    }

    public function add(Oace $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Oace $entity, bool $flush = true): Oace
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Oace $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
