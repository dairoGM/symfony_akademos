<?php

namespace App\Repository\Personal;

use App\Entity\Personal\GradoAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GradoAcademico>
 *
 * @method GradoAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method GradoAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method GradoAcademico[]    findAll()
 * @method GradoAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradoAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GradoAcademico::class);
    }

    public function add(GradoAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(GradoAcademico $entity, bool $flush = true): GradoAcademico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(GradoAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
