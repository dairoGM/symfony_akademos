<?php

namespace App\Repository\Personal;

use App\Entity\Personal\TipoOrganizacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoOrganizacion>
 *
 * @method TipoOrganizacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOrganizacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOrganizacion[]    findAll()
 * @method TipoOrganizacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOrganizacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOrganizacion::class);
    }

    public function add(TipoOrganizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoOrganizacion $entity, bool $flush = true): TipoOrganizacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoOrganizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
