<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\ModalidadPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModalidadPrograma>
 *
 * @method ModalidadPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModalidadPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModalidadPrograma[]    findAll()
 * @method ModalidadPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModalidadProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModalidadPrograma::class);
    }

    public function add(ModalidadPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ModalidadPrograma $entity, bool $flush = true): ModalidadPrograma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ModalidadPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
