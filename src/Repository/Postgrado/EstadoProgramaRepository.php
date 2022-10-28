<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\EstadoPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstadoPrograma>
 *
 * @method EstadoPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoPrograma[]    findAll()
 * @method EstadoPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoPrograma::class);
    }

    public function add(EstadoPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(EstadoPrograma $entity, bool $flush = true): EstadoPrograma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(EstadoPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
