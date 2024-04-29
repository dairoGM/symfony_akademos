<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\FichaSalida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichaSalida>
 *
 * @method FichaSalida|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichaSalida|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichaSalida[]    findAll()
 * @method FichaSalida[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichaSalidaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichaSalida::class);
    }

    public function add(FichaSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(FichaSalida $entity, bool $flush = true): FichaSalida
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(FichaSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
