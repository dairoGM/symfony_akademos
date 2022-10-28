<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\MiembrosComision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MiembrosComision>
 *
 * @method MiembrosComision|null find($id, $lockMode = null, $lockVersion = null)
 * @method MiembrosComision|null findOneBy(array $criteria, array $orderBy = null)
 * @method MiembrosComision[]    findAll()
 * @method MiembrosComision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiembrosComisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MiembrosComision::class);
    }

    public function add(MiembrosComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(MiembrosComision $entity, bool $flush = true): MiembrosComision
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(MiembrosComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
