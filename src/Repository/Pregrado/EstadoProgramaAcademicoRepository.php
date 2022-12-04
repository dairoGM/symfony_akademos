<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\EstadoProgramaAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstadoProgramaAcademico>
 *
 * @method EstadoProgramaAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoProgramaAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoProgramaAcademico[]    findAll()
 * @method EstadoProgramaAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoProgramaAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoProgramaAcademico::class);
    }

    public function add(EstadoProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(EstadoProgramaAcademico $entity, bool $flush = true): EstadoProgramaAcademico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(EstadoProgramaAcademico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
