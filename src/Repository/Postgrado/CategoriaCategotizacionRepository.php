<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\CategoriaCategotizacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaCategotizacion>
 *
 * @method CategoriaCategotizacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaCategotizacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaCategotizacion[]    findAll()
 * @method CategoriaCategotizacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaCategotizacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaCategotizacion::class);
    }

    public function add(CategoriaCategotizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaCategotizacion $entity, bool $flush = true): CategoriaCategotizacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaCategotizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
