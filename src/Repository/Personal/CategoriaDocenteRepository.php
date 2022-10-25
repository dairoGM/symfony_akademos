<?php

namespace App\Repository\Personal;

use App\Entity\Personal\CategoriaDocente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaDocente>
 *
 * @method CategoriaDocente|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaDocente|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaDocente[]    findAll()
 * @method CategoriaDocente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaDocenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaDocente::class);
    }

    public function add(CategoriaDocente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaDocente $entity, bool $flush = true): CategoriaDocente
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaDocente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
