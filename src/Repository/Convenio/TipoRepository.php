<?php

namespace App\Repository\Convenio;

use App\Entity\Convenio\Tipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tipo>
 *
 * @method Tipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tipo[]    findAll()
 * @method Tipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tipo::class);
    }

    public function add(Tipo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Tipo $entity, bool $flush = true): Tipo
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Tipo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
