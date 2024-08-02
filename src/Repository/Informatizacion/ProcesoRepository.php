<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\Proceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proceso>
 *
 * @method Proceso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proceso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proceso[]    findAll()
 * @method Proceso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proceso::class);
    }

    public function add(Proceso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Proceso $entity, bool $flush = true): Proceso
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Proceso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
