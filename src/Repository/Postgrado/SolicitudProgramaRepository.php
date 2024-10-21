<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudPrograma>
 *
 * @method SolicitudPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudPrograma[]    findAll()
 * @method SolicitudPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudPrograma::class);
    }

    public function add(SolicitudPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudPrograma $entity, bool $flush = true): SolicitudPrograma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSolicitudes()
    {
        $qb = $this->createQueryBuilder('qb')
            ->innerJoin('qb.estadoPrograma', 'ep')
            ->where("ep.id NOT IN(:valuesItems)")->setParameter('valuesItems', array_values([7]))
            ->orderBy('qb.id', 'desc');

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getSolicitudesSinVotacion()
    {
        // DQL para la subconsulta
        $subQueryDQL = 'SELECT solicitudPrograma.id 
                    FROM App\Entity\Postgrado\SolicitudProgramaVotacion spv
                    JOIN spv.solicitudPrograma solicitudPrograma';

        // Consulta principal
        $qb = $this->createQueryBuilder('solicitud');
        $qb->innerJoin('solicitud.estadoPrograma', 'estadoPrograma')
            ->where('estadoPrograma.id NOT IN(:excludedIds)')
            ->setParameter('excludedIds', [7])
            ->andWhere($qb->expr()->notIn('solicitud.id', "($subQueryDQL)")) // Uso de la subconsulta DQL
            ->orderBy('solicitud.id', 'DESC');

        return $qb->getQuery()->getResult();
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
            ->join('qb.universidad', 'c')
            ->join('qb.tipoPrograma', 'tp')
            ->join('qb.categoriaAcreditacion', 'ca')
            ->join('c.estructura', 'e')
            ->join('e.estructura', 'e1')
            ->leftJoin('App\Entity\Evaluacion\CategoriaAcreditacionPosgrado', 'b', Join::WITH, 'qb.id = b.solicitudPrograma');

        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
