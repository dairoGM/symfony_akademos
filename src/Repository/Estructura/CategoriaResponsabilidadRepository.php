<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\CategoriaResponsabilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaResponsabilidad>
 *
 * @method CategoriaResponsabilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaResponsabilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaResponsabilidad[]    findAll()
 * @method CategoriaResponsabilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaResponsabilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaResponsabilidad::class);
    }

    public function add(CategoriaResponsabilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
   
    public function edit(CategoriaResponsabilidad $entity, bool $flush = true): CategoriaResponsabilidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaResponsabilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
