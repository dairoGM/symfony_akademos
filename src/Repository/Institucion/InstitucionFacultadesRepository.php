<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionFacultades;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionFacultades>
 *
 * @method InstitucionFacultades|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionFacultades|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionFacultades[]    findAll()
 * @method InstitucionFacultades[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionFacultadesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionFacultades::class);
    }

    public function add(InstitucionFacultades $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionFacultades $entity, bool $flush = true): InstitucionFacultades
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionFacultades $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
