<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\SolicitudProgramaInstitucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaInstitucion>
 *
 * @method SolicitudProgramaInstitucion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaInstitucion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaInstitucion[]    findAll()
 * @method SolicitudProgramaInstitucion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaInstitucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaInstitucion::class);
    }

    public function add(SolicitudProgramaInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaInstitucion $entity, bool $flush = true): SolicitudProgramaInstitucion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
