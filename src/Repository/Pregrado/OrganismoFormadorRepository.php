<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\OrganismoFormador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganismoFormador>
 *
 * @method OrganismoFormador|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganismoFormador|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganismoFormador[]    findAll()
 * @method OrganismoFormador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismoFormadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganismoFormador::class);
    }

    public function add(OrganismoFormador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(OrganismoFormador $entity, bool $flush = true): OrganismoFormador
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(OrganismoFormador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
