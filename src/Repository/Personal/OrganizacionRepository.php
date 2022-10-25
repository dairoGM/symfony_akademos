<?php

namespace App\Repository\Personal;

use App\Entity\Personal\Organizacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Organizacion>
 *
 * @method Organizacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organizacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organizacion[]    findAll()
 * @method Organizacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organizacion::class);
    }

    public function add(Organizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Organizacion $entity, bool $flush = true): Organizacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Organizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
