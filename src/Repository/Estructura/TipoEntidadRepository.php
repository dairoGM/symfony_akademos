<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\TipoEntidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoEntidad>
 *
 * @method TipoEntidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoEntidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoEntidad[]    findAll()
 * @method TipoEntidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoEntidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoEntidad::class);
    }

    public function add(TipoEntidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
  
    public function edit(TipoEntidad $entity, bool $flush = true): TipoEntidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }


    public function remove(TipoEntidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
