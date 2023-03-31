<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\HistoricoEstadoProgramaAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoricoEstadoProgramaAcademico>
 *
 * @method HistoricoEstadoProgramaAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoricoEstadoProgramaAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoricoEstadoProgramaAcademico[]    findAll()
 * @method HistoricoEstadoProgramaAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoricoEstadoProgramaAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoricoEstadoProgramaAcademico::class);
    }

    public function add(HistoricoEstadoProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(HistoricoEstadoProgramaAcademico $entity, bool $flush = true): HistoricoEstadoProgramaAcademico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(HistoricoEstadoProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
