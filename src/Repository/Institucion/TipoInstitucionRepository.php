<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\TipoInstitucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoInstitucion>
 *
 * @method TipoInstitucion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoInstitucion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoInstitucion[]    findAll()
 * @method TipoInstitucion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoInstitucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoInstitucion::class);
    }

    public function add(TipoInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoInstitucion $entity, bool $flush = true): TipoInstitucion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
