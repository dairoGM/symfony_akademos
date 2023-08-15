<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionCum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionCum>
 *
 * @method InstitucionCum|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionCum|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionCum[]    findAll()
 * @method InstitucionCum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionCumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionCum::class);
    }

    public function add(InstitucionCum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionCum $entity, bool $flush = true): InstitucionCum
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionCum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
