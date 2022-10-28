<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\Universidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Universidad>
 *
 * @method Universidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Universidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Universidad[]    findAll()
 * @method Universidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UniversidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Universidad::class);
    }

    public function add(Universidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Universidad $entity, bool $flush = true): Universidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Universidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
