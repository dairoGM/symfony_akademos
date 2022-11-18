<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\RolRedesNacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RolRedesNacional>
 *
 * @method RolRedesNacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolRedesNacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolRedesNacional[]    findAll()
 * @method RolRedesNacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolRedesNacionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolRedesNacional::class);
    }

    public function add(RolRedesNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RolRedesNacional $entity, bool $flush = true): RolRedesNacional
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RolRedesNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
