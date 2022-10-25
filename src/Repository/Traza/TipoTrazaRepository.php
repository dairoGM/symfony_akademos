<?php

namespace App\Repository\Traza;

use App\Entity\Traza\TipoTraza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoTraza>
 *
 * @method TipoTraza|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoTraza|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoTraza[]    findAll()
 * @method TipoTraza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoTrazaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoTraza::class);
    }

    public function add(TipoTraza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoTraza $entity, bool $flush = true): TipoTraza
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoTraza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
