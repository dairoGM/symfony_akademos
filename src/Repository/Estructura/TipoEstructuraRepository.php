<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\TipoEstructura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoEstructura>
 *
 * @method TipoEstructura|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoEstructura|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoEstructura[]    findAll()
 * @method TipoEstructura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoEstructuraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoEstructura::class);
    }

    public function add(TipoEstructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoEstructura $entity, bool $flush = true): TipoEstructura
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoEstructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
