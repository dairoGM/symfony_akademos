<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\TipoProgramaAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoProgramaAcademico>
 *
 * @method TipoProgramaAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoProgramaAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoProgramaAcademico[]    findAll()
 * @method TipoProgramaAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoProgramaAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoProgramaAcademico::class);
    }

    public function add(TipoProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoProgramaAcademico $entity, bool $flush = true): TipoProgramaAcademico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
