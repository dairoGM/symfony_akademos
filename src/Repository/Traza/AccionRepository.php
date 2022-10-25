<?php

namespace App\Repository\Traza;

use App\Entity\Traza\Accion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accion>
 *
 * @method Accion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accion[]    findAll()
 * @method Accion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accion::class);
    }

    public function add(Accion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Accion $entity, bool $flush = true): Accion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Accion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
