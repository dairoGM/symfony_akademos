<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\TipoSistema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoSistema>
 *
 * @method TipoSistema|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoSistema|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoSistema[]    findAll()
 * @method TipoSistema[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoSistemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoSistema::class);
    }

    public function add(TipoSistema $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoSistema $entity, bool $flush = true): TipoSistema
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoSistema $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
