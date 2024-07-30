<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\SistemaOperativo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SistemaOperativo>
 *
 * @method SistemaOperativo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaOperativo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaOperativo[]    findAll()
 * @method SistemaOperativo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaOperativoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaOperativo::class);
    }

    public function add(SistemaOperativo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SistemaOperativo $entity, bool $flush = true): SistemaOperativo
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SistemaOperativo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
