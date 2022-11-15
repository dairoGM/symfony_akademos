<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionMembresia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionMembresia>
 *
 * @method InstitucionMembresia|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionMembresia|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionMembresia[]    findAll()
 * @method InstitucionMembresia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionMembresiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionMembresia::class);
    }

    public function add(InstitucionMembresia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionMembresia $entity, bool $flush = true): InstitucionMembresia
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionMembresia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
