<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\FichaSalidaConceptoGasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichaSalidaConceptoGasto>
 *
 * @method FichaSalidaConceptoGasto|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichaSalidaConceptoGasto|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichaSalidaConceptoGasto[]    findAll()
 * @method FichaSalidaConceptoGasto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichaSalidaConceptoGastoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichaSalidaConceptoGasto::class);
    }

    public function add(FichaSalidaConceptoGasto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(FichaSalidaConceptoGasto $entity, bool $flush = true): FichaSalidaConceptoGasto
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(FichaSalidaConceptoGasto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
