<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Nivel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nivel>
 *
 * @method Nivel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nivel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nivel[]    findAll()
 * @method Nivel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nivel::class);
    }

    public function add(Nivel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Nivel $entity, bool $flush = true): Nivel
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Nivel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
