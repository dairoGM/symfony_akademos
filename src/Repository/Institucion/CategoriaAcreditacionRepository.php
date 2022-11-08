<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\CategoriaAcreditacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaAcreditacion>
 *
 * @method CategoriaAcreditacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaAcreditacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaAcreditacion[]    findAll()
 * @method CategoriaAcreditacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaAcreditacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaAcreditacion::class);
    }

    public function add(CategoriaAcreditacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaAcreditacion $entity, bool $flush = true): CategoriaAcreditacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaAcreditacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
