<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionFum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionFum>
 *
 * @method InstitucionFum|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionFum|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionFum[]    findAll()
 * @method InstitucionFum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionFumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionFum::class);
    }

    public function add(InstitucionFum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionFum $entity, bool $flush = true): InstitucionFum
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionFum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
