<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
    public function getSolicitudProgramaAcademicoAprobadoFiltro($estadoIds, $id = null)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select("qb.nombre, qb.id")
            ->where("qb.estadoProgramaAcademico IN(:valuesItems)")->setParameter('valuesItems', array_values($estadoIds))
            ->orderBy('qb.id', 'desc');
        if (!empty($id)) {
            $qb->andWhere("qb.centroRector = $id");
        }
        $qb->orderBy('qb.nombre');
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

    public function getSolicitudProgramaAcademicoAprobadoPorRamaCiencia($tipoProgramaAcademico)
    {
        $query = "SELECT nombre, color,
                 (SELECT count(*) 
                    FROM pregrado.tbd_solicitud_programa_academico t1_ 
                    INNER JOIN postgrado.tbn_rama_ciencia t0_ ON t1_.rama_ciencia_id = t0_.id 
                    INNER JOIN pregrado.tbn_tipo_programa_academico t2_ ON t1_.tipo_programa_academico_id = t2_.id 
                    WHERE 
                    t1_.estado_programa_academico_id = 2 AND 
                    t2_.id = $tipoProgramaAcademico and r1.id = t0_.id)as total
                    FROM postgrado.tbn_rama_ciencia r1
                    order by total desc";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        return $temp->fetchAllAssociative() ;

//        $qb = $this->createQueryBuilder('qb')
//            ->select('rm.nombre,rm.color, count(qb.id) as total')
//            ->join('qb.ramaCiencia', 'rm')
//            ->join('qb.tipoProgramaAcademico', 'tp')
//            ->where("qb.estadoProgramaAcademico = 2 and tp.id = '$tipoProgramaAcademico'")
//            ->groupBy('rm.nombre, rm.color');
//
//        $resul = $qb->getQuery()->getResult();
//        return $resul;
    }

    public function getSolicitudProgramaAcademicoAprobadoPorModalidad($tipoProgramaAcademico)
    {
        $query = "select 
                sum(case when pregrado.tbr_solicitud_programa_academico_institucion.modalidad_diurno then 1 else 0 end )as diurno,
                sum(case when pregrado.tbr_solicitud_programa_academico_institucion.modalidad_adistancia then 1 else 0 end )as adistancia,
                sum(case when pregrado.tbr_solicitud_programa_academico_institucion.modalidad_por_encuentro then 1 else 0 end )as por_encuentro
                from  pregrado.tbr_solicitud_programa_academico_institucion
                join pregrado.tbd_solicitud_programa_academico on tbd_solicitud_programa_academico.id = tbr_solicitud_programa_academico_institucion.solicitud_programa_academico_id
                where estado_programa_academico_id = 2 and tipo_programa_academico_id='$tipoProgramaAcademico'";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        return $temp->fetchAllAssociative()[0];
    }


    public function getSolicitudProgramaAcademicoAprobadoPorCentroRector($tipoProgramaAcademico)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('cr.siglas as name, count(qb.id) as y')
            ->join('qb.centroRector', 'cr')
            ->join('qb.tipoProgramaAcademico', 'tp')
            ->where("qb.estadoProgramaAcademico = 2 and tp.id = '$tipoProgramaAcademico'")
            ->groupBy('cr.siglas');

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getSolicitudProgramaAcademicoAprobadoByTipo($estadoIds, $tipoProgramaAcademico)
    {
        $qb = $this->createQueryBuilder('qb')
            ->join('qb.tipoProgramaAcademico', 'tp')
            ->where("tp.id = '$tipoProgramaAcademico' and qb.estadoProgramaAcademico IN(:valuesItems)")->setParameter('valuesItems', array_values($estadoIds))
            ->orderBy('qb.id', 'desc');

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }


    public function getSolicitudProgramaAcademicoAprobadoPorOrganismoDemandante($tipoProgramaAcademico)
    {
//        $qb = $this->createQueryBuilder('qb')
//            ->select('od.siglas as name, count(qb.id) as y')
//            ->join('qb.organismoDemandante', 'od')
//            ->join('qb.tipoProgramaAcademico', 'tp')
//            ->where("qb.estadoProgramaAcademico = 2 and tp.id = '$tipoProgramaAcademico'")
//            ->groupBy('od.siglas');
//
//        $resul = $qb->getQuery()->getResult();
//        return $resul;
        $query = "SELECT 
                    siglas as name,
                    (select count(*) from pregrado.tbd_solicitud_programa_academico
                         where pregrado.tbd_solicitud_programa_academico.estado_programa_academico_id = 2 and pregrado.tbd_solicitud_programa_academico.tipo_programa_academico_id =$tipoProgramaAcademico  and
                         pregrado.tbd_solicitud_programa_academico.organismo_demandante_id = pregrado.tbn_organismo_demandante.id) as y
                    FROM pregrado.tbn_organismo_demandante where activo = true
                    order by y desc";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        return $temp->fetchAllAssociative();
    }

    public function getSolicitudProgramaAcademicoAprobadoPorOrganismoFormador($tipoProgramaAcademico)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('cr.nombre as name, count(qb.id) as y')
            ->join('qb.organismoFormador', 'cr')
            ->join('qb.tipoProgramaAcademico', 'tp')
            ->where("qb.estadoProgramaAcademico = 2 and tp.id = '$tipoProgramaAcademico'")
            ->groupBy('cr.nombre');

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getProgramasV2()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select(
                "qb.id, 
                        concat('(',e1.siglas,') ', e1.nombre) as nombre_siglas_organismo, 
                        concat('(',c.siglas,') ', c.nombre) as nombre_siglas, 
                        qb.nombre,
                        tp.nombre as nombreTipoPrograma,
                        ca.nombre as catAcreditacion,
                         DateFormat(b.fechaEmision, 'DD/MM/YYYY') as fechaEmision,
                         b.numeroPleno,
                         b.numeroAcuerdoPleno,
                         b.annosVigenciaCategoriaAcreditacion")
            ->join('qb.centroRector', 'c')
            ->join('qb.tipoProgramaAcademico', 'tp')
            ->join('qb.categoriaAcreditacion', 'ca')
            ->join('c.estructura', 'e')
            ->join('e.estructura', 'e1')
            ->leftJoin('App\Entity\Evaluacion\CategoriaAcreditacionPregrado', 'b', Join::WITH, 'qb.id = b.solicitudProgramaAcademico');

        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
