<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\Tramite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tramite>
 *
 * @method Tramite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tramite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tramite[]    findAll()
 * @method Tramite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TramiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tramite::class);
    }

    public function add(Tramite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Tramite $entity, bool $flush = true): Tramite
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Tramite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
