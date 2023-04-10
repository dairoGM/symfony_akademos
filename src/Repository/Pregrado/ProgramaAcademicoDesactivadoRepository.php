<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\ProgramaAcademicoDesactivado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgramaAcademicoDesactivado>
 *
 * @method ProgramaAcademicoDesactivado|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgramaAcademicoDesactivado|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgramaAcademicoDesactivado[]    findAll()
 * @method ProgramaAcademicoDesactivado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramaAcademicoDesactivadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgramaAcademicoDesactivado::class);
    }

    public function add(ProgramaAcademicoDesactivado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ProgramaAcademicoDesactivado $entity, bool $flush = true): ProgramaAcademicoDesactivado
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ProgramaAcademicoDesactivado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getSolicitudProgramaAcademicoAprobadoDesactivado($estadoIds)
    {
        $qb = $this->createQueryBuilder('spa')
            ->join('spa.solicitudProgramaAcademico', 'qb')
            ->where("qb.estadoProgramaAcademico IN(:valuesItems)")->setParameter('valuesItems', array_values($estadoIds));
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
