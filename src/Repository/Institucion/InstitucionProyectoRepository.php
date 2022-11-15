<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionProyecto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionProyecto>
 *
 * @method InstitucionProyecto|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionProyecto|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionProyecto[]    findAll()
 * @method InstitucionProyecto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionProyectoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionProyecto::class);
    }

    public function add(InstitucionProyecto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionProyecto $entity, bool $flush = true): InstitucionProyecto
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionProyecto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
