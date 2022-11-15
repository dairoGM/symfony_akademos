<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionSedes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionSedes>
 *
 * @method InstitucionSedes|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionSedes|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionSedes[]    findAll()
 * @method InstitucionSedes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionSedesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionSedes::class);
    }

    public function add(InstitucionSedes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionSedes $entity, bool $flush = true): InstitucionSedes
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionSedes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
