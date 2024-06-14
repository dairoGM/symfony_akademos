<?php

namespace App\Repository\Economia;

use App\Entity\Economia\ConceptoGasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConceptoGasto>
 *
 * @method ConceptoGasto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConceptoGasto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConceptoGasto[]    findAll()
 * @method ConceptoGasto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConceptoGastoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConceptoGasto::class);
    }

    public function add(ConceptoGasto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ConceptoGasto $entity, bool $flush = true): ConceptoGasto
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ConceptoGasto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
