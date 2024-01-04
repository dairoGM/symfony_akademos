<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaAcademicoComisionNacional>
 *
 * @method SolicitudProgramaAcademicoComisionNacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaAcademicoComisionNacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaAcademicoComisionNacional[]    findAll()
 * @method SolicitudProgramaAcademicoComisionNacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaAcademicoComisionNacionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaAcademicoComisionNacional::class);
    }

    public function add(SolicitudProgramaAcademicoComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaAcademicoComisionNacional $entity, bool $flush = true): SolicitudProgramaAcademicoComisionNacional
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaAcademicoComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getComisionNacional($solicitudId)
    {
        $query = "SELECT tbd_comision_nacional.nombre
                        FROM pregrado.tbr_solicitud_programa_academico_comision_nacional
                        join pregrado.tbd_comision_nacional on tbd_comision_nacional.id = tbr_solicitud_programa_academico_comision_nacional.comision_nacional_id
                        where pregrado.tbr_solicitud_programa_academico_comision_nacional.solicitud_programa_academico_id = $solicitudId";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        $temp = $temp->fetchAllAssociative();
        return isset($temp[0]) ? $temp[0]['nombre'] : null;
    }
    public function getIdComisionNacional($solicitudId)
    {
        $query = "SELECT tbd_comision_nacional.id
                        FROM pregrado.tbr_solicitud_programa_academico_comision_nacional
                        join pregrado.tbd_comision_nacional on tbd_comision_nacional.id = tbr_solicitud_programa_academico_comision_nacional.comision_nacional_id
                        where pregrado.tbr_solicitud_programa_academico_comision_nacional.solicitud_programa_academico_id = $solicitudId";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        $temp = $temp->fetchAllAssociative();
        return isset($temp[0]) ? $temp[0]['id'] : null;
    }
}
