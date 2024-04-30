<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\EstadoFichaSalida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstadoFichaSalida>
 *
 * @method EstadoFichaSalida|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoFichaSalida|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoFichaSalida[]    findAll()
 * @method EstadoFichaSalida[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoFichaSalidaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoFichaSalida::class);
    }

    public function add(EstadoFichaSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(EstadoFichaSalida $entity, bool $flush = true): EstadoFichaSalida
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(EstadoFichaSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
