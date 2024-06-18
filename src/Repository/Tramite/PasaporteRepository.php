<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\Pasaporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pasaporte>
 *
 * @method Pasaporte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pasaporte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pasaporte[]    findAll()
 * @method Pasaporte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasaporteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pasaporte::class);
    }

    public function add(Pasaporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Pasaporte $entity, bool $flush = true): Pasaporte
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Pasaporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
