<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudPrograma>
 *
 * @method SolicitudPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudPrograma[]    findAll()
 * @method SolicitudPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudPrograma::class);
    }

    public function add(SolicitudPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudPrograma $entity, bool $flush = true): SolicitudPrograma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSolicitudes()
    {
        $qb = $this->createQueryBuilder('qb')
            ->innerJoin('qb.estadoPrograma', 'ep')
            ->where("ep.id NOT IN(:valuesItems)")->setParameter('valuesItems', array_values([7]))
            ->orderBy('qb.id', 'desc');

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
