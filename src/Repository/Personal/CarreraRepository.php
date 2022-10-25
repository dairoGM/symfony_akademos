<?php

namespace App\Repository\Personal;

use App\Entity\Personal\Carrera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carrera>
 *
 * @method Carrera|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carrera|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carrera[]    findAll()
 * @method Carrera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarreraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carrera::class);
    }

    public function add(Carrera $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Carrera $entity, bool $flush = true): Carrera
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Carrera $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
