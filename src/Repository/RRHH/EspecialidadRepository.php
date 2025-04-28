<?php

namespace App\Repository\RRHH;

use App\Entity\RRHH\Especialidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Especialidad>
 *
 * @method Especialidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Especialidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Especialidad[]    findAll()
 * @method Especialidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspecialidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Especialidad::class);
    }

    public function add(Especialidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Especialidad $entity, bool $flush = true): Especialidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Especialidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
