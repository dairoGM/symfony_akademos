<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Municipio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Municipio>
 *
 * @method Municipio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Municipio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Municipio[]    findAll()
 * @method Municipio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Municipio::class);
    }
  
    public function add(Municipio $entity, bool $flush = true): Municipio
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }
  
    public function edit(Municipio $entity, bool $flush = true): Municipio
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Municipio $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
