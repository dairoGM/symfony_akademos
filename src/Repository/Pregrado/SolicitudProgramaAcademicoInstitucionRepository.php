<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
