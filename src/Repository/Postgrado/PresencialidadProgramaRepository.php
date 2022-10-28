<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\PresencialidadPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PresencialidadPrograma>
 *
 * @method PresencialidadPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method PresencialidadPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method PresencialidadPrograma[]    findAll()
 * @method PresencialidadPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresencialidadProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PresencialidadPrograma::class);
    }

    public function add(PresencialidadPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PresencialidadPrograma $entity, bool $flush = true): PresencialidadPrograma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PresencialidadPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
