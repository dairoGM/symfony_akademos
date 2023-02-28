<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\CursoAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CursoAcademico>
 *
 * @method CursoAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method CursoAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method CursoAcademico[]    findAll()
 * @method CursoAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursoAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CursoAcademico::class);
    }

    public function add(CursoAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CursoAcademico $entity, bool $flush = true): CursoAcademico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CursoAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
