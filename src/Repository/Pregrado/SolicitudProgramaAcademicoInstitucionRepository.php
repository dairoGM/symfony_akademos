<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaAcademicoInstitucion>
 *
 * @method SolicitudProgramaAcademicoInstitucion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaAcademicoInstitucion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaAcademicoInstitucion[]    findAll()
 * @method SolicitudProgramaAcademicoInstitucion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaAcademicoInstitucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaAcademicoInstitucion::class);
    }

    public function add(SolicitudProgramaAcademicoInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaAcademicoInstitucion $entity, bool $flush = true): SolicitudProgramaAcademicoInstitucion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaAcademicoInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getSolicitudProgramaAcademicoAprobadoPorCategoriaAcreditacion($tipoProgramaAcademico)
    {
        $query = "SELECT t2.nombre,t2.color, count(*) as total
                FROM pregrado.tbd_solicitud_programa_academico t0_ 
                INNER JOIN pregrado.tbn_tipo_programa_academico t1_ ON t0_.tipo_programa_academico_id = t1_.id 
                INNER JOIN institucion.tbn_categoria_acreditacion t2 on t2.id = t0_.categoria_acreditacion_id
                WHERE t1_.id = '$tipoProgramaAcademico' AND t0_.estado_programa_academico_id = 2
                group by t2.nombre,t2.color
                order by t2.nombre ";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        return $temp->fetchAllAssociative();

//        $qb = $this->createQueryBuilder('qa')
//            ->select('ca.nombre,ca.color, count(qa.id) as total')
//            ->join('qa.solicitudProgramaAcademico', 's')
//            ->join('s.tipoProgramaAcademico', 'tp')
//            ->join('s.categoriaAcreditacion', 'ca')
//            ->where("s.estadoProgramaAcademico = 2  and tp.id = '$tipoProgramaAcademico'")
//            ->groupBy('ca.nombre, ca.color');
//         $resul = $qb->getQuery()->getResult();
//        return $resul;
    }

    public function getProgramasV2()
    {
        $query = "SELECT 
                t0_.id AS id_progr_inst, 
                t1_.id AS id ,
                '(' || t2_.siglas || ') ' || t2_.nombre AS nombre_siglas_organismo,
                '(' || t3_.siglas || ') ' || t3_.nombre AS nombre_siglas,
                t1_.nombre AS nombre, 
                t4_.nombre AS nombre_tipo_programa,
                t5_.nombre AS cat_acreditacion,
                to_char(t6_.fecha_emision,'DD/MM/YYYY') AS fecha_emision,
                t6_.numero_pleno AS numero_pleno,
                t6_.numero_acuerdo_pleno AS numero_acuerdo_pleno,
                t6_.annos_vigencia_categoria_acreditacion AS annos_vigencia_categoria_acreditacion
                FROM pregrado.tbr_solicitud_programa_academico_institucion t0_
                INNER JOIN pregrado.tbd_solicitud_programa_academico t1_ ON t0_.solicitud_programa_academico_id = t1_.id 
                INNER JOIN institucion.tbd_institucion t3_ ON t0_.institucion_id = t3_.id
                INNER JOIN pregrado.tbn_tipo_programa_academico t4_ ON t1_.tipo_programa_academico_id = t4_.id
                INNER JOIN institucion.tbn_categoria_acreditacion t5_ ON t0_.categoria_acreditacion_id = t5_.id
                INNER JOIN estructura.tbd_estructura t7_ ON t3_.estructura_id = t7_.id 
                INNER JOIN estructura.tbd_estructura t2_ ON t7_.estructura_id = t2_.id
                LEFT JOIN evaluacion.temp_categoria_acreditacion_pregrado t6_ ON (t1_.id = t6_.solicitud_programa_academico_id and t0_.institucion_id = t6_.institucion_id  ) 
                
                ORDER BY t1_.nombre ASC";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        return $temp->fetchAllAssociative();

//        $qb = $this->createQueryBuilder('u')
//            ->select(
//                "u.id as idProgrInst,
//                        qb.id,
//                        concat('(',e1.siglas,') ', e1.nombre) as nombre_siglas_organismo,
//                        concat('(',c.siglas,') ', c.nombre) as nombre_siglas,
//                        qb.nombre,
//                        tp.nombre as nombreTipoPrograma,
//                        ca.nombre as catAcreditacion,
//                         DateFormat(b.fechaEmision, 'DD/MM/YYYY') as fechaEmision,
//                         b.numeroPleno,
//                         b.numeroAcuerdoPleno,
//                         b.annosVigenciaCategoriaAcreditacion")
//            ->join('u.solicitudProgramaAcademico', 'qb')
//            ->join('u.institucion', 'c')
//            ->join('qb.tipoProgramaAcademico', 'tp')
//            ->join('u.categoriaAcreditacion', 'ca')
//            ->join('c.estructura', 'e')
//            ->join('e.estructura', 'e1')
//            ->leftJoin('App\Entity\Evaluacion\CategoriaAcreditacionPregrado', 'b', Join::WITH, 'qb.id = b.solicitudProgramaAcademico and u.institucion.id = b.institucion.id');
//
//        $qb->orderBy('qb.nombre');
//
//        $resul = $qb->getQuery()->getResult();
//        return $resul;
    }
}
