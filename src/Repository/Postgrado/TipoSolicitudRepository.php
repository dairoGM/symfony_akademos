<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\TipoSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoSolicitud>
 *
 * @method TipoSolicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoSolicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoSolicitud[]    findAll()
 * @method TipoSolicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoSolicitud::class);
    }

    public function add(TipoSolicitud $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoSolicitud $entity, bool $flush = true): TipoSolicitud
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoSolicitud $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
