<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionEditorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionEditorial>
 *
 * @method InstitucionEditorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionEditorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionEditorial[]    findAll()
 * @method InstitucionEditorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionEditorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionEditorial::class);
    }

    public function add(InstitucionEditorial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionEditorial $entity, bool $flush = true): InstitucionEditorial
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionEditorial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
