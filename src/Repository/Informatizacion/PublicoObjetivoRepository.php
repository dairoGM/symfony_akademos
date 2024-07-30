<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\PublicoObjetivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PublicoObjetivo>
 *
 * @method PublicoObjetivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicoObjetivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicoObjetivo[]    findAll()
 * @method PublicoObjetivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicoObjetivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicoObjetivo::class);
    }

    public function add(PublicoObjetivo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PublicoObjetivo $entity, bool $flush = true): PublicoObjetivo
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PublicoObjetivo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
