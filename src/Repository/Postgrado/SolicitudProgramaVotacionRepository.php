<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudProgramaVotacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaVotacion>
 *
 * @method SolicitudProgramaVotacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaVotacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaVotacion[]    findAll()
 * @method SolicitudProgramaVotacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaVotacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaVotacion::class);
    }

    public function add(SolicitudProgramaVotacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaVotacion $entity, bool $flush = true): SolicitudProgramaVotacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaVotacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function existeVoto($personaId)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id')
            ->innerJoin('qb.miembrosCopep', 'mC')
            ->innerJoin('mC.miembro', 'p')
            ->where("p.id = $personaId");

        $resul = $qb->getQuery()->getResult();
        return isset($resul[0]) ? true : false;
    }
}
