<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\Visibilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visibilidad>
 *
 * @method Visibilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visibilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visibilidad[]    findAll()
 * @method Visibilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisibilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visibilidad::class);
    }

    public function add(Visibilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Visibilidad $entity, bool $flush = true): Visibilidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Visibilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
