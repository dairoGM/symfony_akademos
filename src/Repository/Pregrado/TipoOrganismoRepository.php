<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\TipoOrganismo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoOrganismo>
 *
 * @method TipoOrganismo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOrganismo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOrganismo[]    findAll()
 * @method TipoOrganismo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOrganismoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOrganismo::class);
    }

    public function add(TipoOrganismo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoOrganismo $entity, bool $flush = true): TipoOrganismo
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoOrganismo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
