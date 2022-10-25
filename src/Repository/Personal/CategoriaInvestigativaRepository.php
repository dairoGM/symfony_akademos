<?php

namespace App\Repository\Personal;

use App\Entity\Personal\CategoriaInvestigativa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaInvestigativa>
 *
 * @method CategoriaInvestigativa|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaInvestigativa|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaInvestigativa[]    findAll()
 * @method CategoriaInvestigativa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaInvestigativaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaInvestigativa::class);
    }

    public function add(CategoriaInvestigativa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaInvestigativa $entity, bool $flush = true): CategoriaInvestigativa
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaInvestigativa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
