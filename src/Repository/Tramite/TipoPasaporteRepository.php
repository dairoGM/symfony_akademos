<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\TipoPasaporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoPasaporte>
 *
 * @method TipoPasaporte|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoPasaporte|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoPasaporte[]    findAll()
 * @method TipoPasaporte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoPasaporteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoPasaporte::class);
    }

    public function add(TipoPasaporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoPasaporte $entity, bool $flush = true): TipoPasaporte
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoPasaporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
