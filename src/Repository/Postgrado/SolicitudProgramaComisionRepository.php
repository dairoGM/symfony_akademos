<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudProgramaComision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaComision>
 *
 * @method SolicitudProgramaComision|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaComision|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaComision[]    findAll()
 * @method SolicitudProgramaComision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaComisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaComision::class);
    }

    public function add(SolicitudProgramaComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaComision $entity, bool $flush = true): SolicitudProgramaComision
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaComision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
