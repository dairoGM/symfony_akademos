<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaAcademicoPlanEstudio>
 *
 * @method SolicitudProgramaAcademicoPlanEstudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaAcademicoPlanEstudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaAcademicoPlanEstudio[]    findAll()
 * @method SolicitudProgramaAcademicoPlanEstudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaAcademicoPlanEstudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaAcademicoPlanEstudio::class);
    }

    public function add(SolicitudProgramaAcademicoPlanEstudio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaAcademicoPlanEstudio $entity, bool $flush = true): SolicitudProgramaAcademicoPlanEstudio
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaAcademicoPlanEstudio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
