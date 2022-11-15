<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionRecursoHumano;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionRecursoHumano>
 *
 * @method InstitucionRecursoHumano|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionRecursoHumano|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionRecursoHumano[]    findAll()
 * @method InstitucionRecursoHumano[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionRecursoHumanoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionRecursoHumano::class);
    }

    public function add(InstitucionRecursoHumano $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionRecursoHumano $entity, bool $flush = true): InstitucionRecursoHumano
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionRecursoHumano $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
