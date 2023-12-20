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
        $qb = $this->createQueryBuilder('qa')
            ->select('ca.nombre,ca.color, count(qa.id) as total')
            ->join('qa.solicitudProgramaAcademico', 's')
            ->join('s.tipoProgramaAcademico', 'tp')
            ->join('s.categoriaAcreditacion', 'ca')
            ->where("s.estadoProgramaAcademico = 2  and tp.id = '$tipoProgramaAcademico'")
            ->groupBy('ca.nombre, ca.color');
         $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
