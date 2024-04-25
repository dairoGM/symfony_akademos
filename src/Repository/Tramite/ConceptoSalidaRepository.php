<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\ConceptoSalida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConceptoSalida>
 *
 * @method ConceptoSalida|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConceptoSalida|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConceptoSalida[]    findAll()
 * @method ConceptoSalida[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConceptoSalidaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConceptoSalida::class);
    }

    public function add(ConceptoSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ConceptoSalida $entity, bool $flush = true): ConceptoSalida
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ConceptoSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
