<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionFacultad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionFacultad>
 *
 * @method InstitucionFacultad|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionFacultad|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionFacultad[]    findAll()
 * @method InstitucionFacultad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionFacultadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionFacultad::class);
    }

    public function add(InstitucionFacultad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionFacultad $entity, bool $flush = true): InstitucionFacultad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionFacultad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
