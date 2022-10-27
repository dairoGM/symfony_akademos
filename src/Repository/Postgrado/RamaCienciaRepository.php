<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\RamaCiencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RamaCiencia>
 *
 * @method RamaCiencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method RamaCiencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method RamaCiencia[]    findAll()
 * @method RamaCiencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RamaCienciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RamaCiencia::class);
    }

    public function add(RamaCiencia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RamaCiencia $entity, bool $flush = true): RamaCiencia
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RamaCiencia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
