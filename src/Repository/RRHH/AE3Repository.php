<?php

namespace App\Repository\RRHH;

use App\Entity\RRHH\AE3;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AE3|null find($id, $lockMode = null, $lockVersion = null)
 * @method AE3|null findOneBy(array $criteria, array $orderBy = null)
 * @method AE3[]    findAll()
 * @method AE3[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AE3Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AE3::class);
    }

    public function add(AE3 $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function edit(AE3 $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(AE3 $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}