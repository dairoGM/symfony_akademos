<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\TipoConectividad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoConectividad>
 *
 * @method TipoConectividad|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoConectividad|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoConectividad[]    findAll()
 * @method TipoConectividad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoConectividadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoConectividad::class);
    }

    public function add(TipoConectividad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoConectividad $entity, bool $flush = true): TipoConectividad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoConectividad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
