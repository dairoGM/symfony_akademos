<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\EnlaceConectividad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EnlaceConectividad>
 *
 * @method EnlaceConectividad|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnlaceConectividad|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnlaceConectividad[]    findAll()
 * @method EnlaceConectividad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnlaceConectividadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnlaceConectividad::class);
    }

    public function add(EnlaceConectividad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(EnlaceConectividad $entity, bool $flush = true): EnlaceConectividad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(EnlaceConectividad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
