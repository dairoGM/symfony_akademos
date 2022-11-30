<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudProgramaDictamen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaDictamen>
 *
 * @method SolicitudProgramaDictamen|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaDictamen|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaDictamen[]    findAll()
 * @method SolicitudProgramaDictamen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaDictamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaDictamen::class);
    }

    public function add(SolicitudProgramaDictamen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaDictamen $entity, bool $flush = true): SolicitudProgramaDictamen
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaDictamen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
