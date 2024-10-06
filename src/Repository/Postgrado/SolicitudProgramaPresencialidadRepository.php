<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudProgramaPresencialidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaPresencialidad>
 *
 * @method SolicitudProgramaPresencialidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaPresencialidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaPresencialidad[]    findAll()
 * @method SolicitudProgramaPresencialidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaPresencialidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaPresencialidad::class);
    }

    public function add(SolicitudProgramaPresencialidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaPresencialidad $entity, bool $flush = true): SolicitudProgramaPresencialidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaPresencialidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
