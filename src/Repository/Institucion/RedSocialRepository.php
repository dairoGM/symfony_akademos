<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\RedSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RedSocial>
 *
 * @method RedSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedSocial[]    findAll()
 * @method RedSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedSocialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedSocial::class);
    }

    public function add(RedSocial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(RedSocial $entity, bool $flush = true): RedSocial
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(RedSocial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
