<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\ComisionNacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComisionNacional>
 *
 * @method ComisionNacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComisionNacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComisionNacional[]    findAll()
 * @method ComisionNacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComisionNacionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComisionNacional::class);
    }

    public function add(ComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ComisionNacional $entity, bool $flush = true): ComisionNacional
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
