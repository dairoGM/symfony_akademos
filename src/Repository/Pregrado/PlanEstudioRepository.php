<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\PlanEstudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanEstudio>
 *
 * @method PlanEstudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanEstudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanEstudio[]    findAll()
 * @method PlanEstudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanEstudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanEstudio::class);
    }

    public function add(PlanEstudio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PlanEstudio $entity, bool $flush = true): PlanEstudio
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PlanEstudio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPlanesEstudio()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select(
                "qb.id, 
                        c.nombre as nombre_carrera,
                        ca.nombre as nombre_curso_academico,
                        qb.annoAprobacion,
                         qb.activo");
        $qb->innerJoin('qb.carrera', 'c');
        $qb->innerJoin('qb.cursoAcademico', 'ca');
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
