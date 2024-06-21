<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\EstadoSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstadoSolicitud>
 *
 * @method EstadoSolicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoSolicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoSolicitud[]    findAll()
 * @method EstadoSolicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoSolicitud::class);
    }

    public function add(EstadoSolicitud $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(EstadoSolicitud $entity, bool $flush = true): EstadoSolicitud
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(EstadoSolicitud $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
