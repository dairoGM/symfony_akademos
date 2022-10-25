<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\CategoriaEstructura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaEstructura>
 *
 * @method CategoriaEstructura|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaEstructura|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaEstructura[]    findAll()
 * @method CategoriaEstructura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaEstructuraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaEstructura::class);
    }

    public function add(CategoriaEstructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaEstructura $entity, bool $flush = true): CategoriaEstructura
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaEstructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
