<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\SolicitudDictamenComision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudDictamenComision>
 *
 * @method SolicitudDictamenComision|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudDictamenComision|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudDictamenComision[]    findAll()
 * @method SolicitudDictamenComision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudDictamenComisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudDictamenComision::class);
    }

    public function add(SolicitudDictamenComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudDictamenComision $entity, bool $flush = true): SolicitudDictamenComision
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudDictamenComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 
}
