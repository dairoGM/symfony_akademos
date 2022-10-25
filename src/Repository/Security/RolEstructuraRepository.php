<?php

namespace App\Repository\Security;

use App\Entity\Security\RolEstructura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RolEstructura>
 *
 * @method RolEstructura|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolEstructura|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolEstructura[]    findAll()
 * @method RolEstructura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolEstructuraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolEstructura::class);
    }

    public function add(RolEstructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RolEstructura $entity, bool $flush = true): RolEstructura
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RolEstructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


}
