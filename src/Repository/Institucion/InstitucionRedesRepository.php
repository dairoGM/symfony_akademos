<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionRedes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionRedes>
 *
 * @method InstitucionRedes|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionRedes|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionRedes[]    findAll()
 * @method InstitucionRedes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionRedesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionRedes::class);
    }

    public function add(InstitucionRedes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionRedes $entity, bool $flush = true): InstitucionRedes
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionRedes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
