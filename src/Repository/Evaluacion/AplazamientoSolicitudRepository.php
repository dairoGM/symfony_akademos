<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\AplazamientoSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AplazamientoSolicitud>
 *
 * @method AplazamientoSolicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method AplazamientoSolicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method AplazamientoSolicitud[]    findAll()
 * @method AplazamientoSolicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AplazamientoSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AplazamientoSolicitud::class);
    }

    public function add(AplazamientoSolicitud $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(AplazamientoSolicitud $entity, bool $flush = true): AplazamientoSolicitud
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(AplazamientoSolicitud $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
