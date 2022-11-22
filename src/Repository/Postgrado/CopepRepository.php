<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\Copep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Copep>
 *
 * @method Copep|null find($id, $lockMode = null, $lockVersion = null)
 * @method Copep|null findOneBy(array $criteria, array $orderBy = null)
 * @method Copep[]    findAll()
 * @method Copep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CopepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Copep::class);
    }

    public function add(Copep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Copep $entity, bool $flush = true): Copep
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Copep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
