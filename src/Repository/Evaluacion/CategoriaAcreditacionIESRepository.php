<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\CategoriaAcreditacionIES;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaAcreditacionIES>
 *
 * @method CategoriaAcreditacionIES|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaAcreditacionIES|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaAcreditacionIES[]    findAll()
 * @method CategoriaAcreditacionIES[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaAcreditacionIESRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaAcreditacionIES::class);
    }

    public function add(CategoriaAcreditacionIES $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaAcreditacionIES $entity, bool $flush = true): CategoriaAcreditacionIES
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaAcreditacionIES $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
