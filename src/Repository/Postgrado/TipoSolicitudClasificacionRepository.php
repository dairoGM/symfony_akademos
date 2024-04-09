<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\TipoSolicitudClasificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoSolicitudClasificacion>
 *
 * @method TipoSolicitudClasificacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoSolicitudClasificacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoSolicitudClasificacion[]    findAll()
 * @method TipoSolicitudClasificacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoSolicitudClasificacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoSolicitudClasificacion::class);
    }

    public function add(TipoSolicitudClasificacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoSolicitudClasificacion $entity, bool $flush = true): TipoSolicitudClasificacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoSolicitudClasificacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
