<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\OrganismoDemandante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganismoDemandante>
 *
 * @method OrganismoDemandante|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganismoDemandante|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganismoDemandante[]    findAll()
 * @method OrganismoDemandante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismoDemandanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganismoDemandante::class);
    }

    public function add(OrganismoDemandante $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(OrganismoDemandante $entity, bool $flush = true): OrganismoDemandante
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(OrganismoDemandante $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
