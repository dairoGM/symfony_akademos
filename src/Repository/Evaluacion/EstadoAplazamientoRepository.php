<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\EstadoAplazamiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstadoAplazamiento>
 *
 * @method EstadoAplazamiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoAplazamiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoAplazamiento[]    findAll()
 * @method EstadoAplazamiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoAplazamientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoAplazamiento::class);
    }

    public function add(EstadoAplazamiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(EstadoAplazamiento $entity, bool $flush = true): EstadoAplazamiento
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(EstadoAplazamiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
