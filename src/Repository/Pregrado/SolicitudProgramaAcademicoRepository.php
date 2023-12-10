<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaAcademico>
 *
 * @method SolicitudProgramaAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaAcademico[]    findAll()
 * @method SolicitudProgramaAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaAcademico::class);
    }

    public function add(SolicitudProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaAcademico $entity, bool $flush = true): SolicitudProgramaAcademico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSolicitudProgramaAcademico($estadoIds)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select("
            qb.id, 
            qb.nombre,
            tp.nombre as tipoProgramaAcademico,             
            od.nombre as organismoDemandante,
            epa.nombre as estadoProgramaAcademico, 
            epa.id as estadoProgramaAcademicoId 
            ")
            ->join('qb.tipoProgramaAcademico', 'tp')
            ->join('qb.organismoDemandante', 'od')
            ->join('qb.estadoProgramaAcademico', 'epa')
            ->where("qb.estadoProgramaAcademico IN(:valuesItems)")->setParameter('valuesItems', array_values($estadoIds))
            ->orderBy('qb.id', 'desc');
        $resul = $qb->getQuery()->getResult();

        return $resul;
    }

    public function getSolicitudProgramaAcademicoAprobado($estadoIds, $id = null)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.estadoProgramaAcademico IN(:valuesItems)")->setParameter('valuesItems', array_values($estadoIds))
            ->orderBy('qb.id', 'desc');
        if (!empty($id)) {
            $qb->andWhere("qb.centroRector = $id");
        }
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getSolicitudProgramaAcademicoAprobadoDesactivado($estadoIds)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.estadoProgramaAcademico IN(:valuesItems)")->setParameter('valuesItems', array_values($estadoIds));

        $subQuery = $this->getEntityManager()->getRepository('App\Entity\Pregrado\ProgramaAcademicoDesactivado')->createQueryBuilder('subQb')
            ->select('spa.id')
            ->innerJoin('subQb.solicitudProgramaAcademico', 'spa')
            ->andWhere("subQb.fechaEliminacion is not null");

        $exp = $qb->expr()->in('qb.id', $subQuery->getDQL());
        $qb->andWhere($exp);

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
    public function getSolicitudProgramaAcademicoAprobadoPorRamaCiencia()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('rm.nombre,rm.color, count(qb.id) as total')
            ->join('qb.ramaCiencia', 'rm')
            ->where('qb.estadoProgramaAcademico = 2')
            ->groupBy('rm.nombre, rm.color') ;

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
    public function getSolicitudProgramaAcademicoAprobadoPorModalidad()
    {
        $query = "select 
            sum(case when pregrado.tbd_solicitud_programa_academico.modalidad_diurno then 1 else 0 end )as diurno,
            sum(case when pregrado.tbd_solicitud_programa_academico.modalidad_adistancia then 1 else 0 end )as adistancia,
            sum(case when pregrado.tbd_solicitud_programa_academico.modalidad_por_encuentro then 1 else 0 end )as por_encuentro
            from pregrado.tbd_solicitud_programa_academico
            
            where estado_programa_academico_id = 2 ";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
       return $temp->fetchAllAssociative()[0];
    }
    public function getSolicitudProgramaAcademicoAprobadoPorCategoriaAcreditacion()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('ca.nombre,ca.color, count(qb.id) as total')
            ->join('qb.categoriaAcreditacion', 'ca')
            ->where('qb.estadoProgramaAcademico = 2')
            ->groupBy('ca.nombre, ca.color') ;

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
