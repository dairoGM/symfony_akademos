<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\ProgramaAcademicoReabierto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgramaAcademicoReabierto>
 *
 * @method ProgramaAcademicoReabierto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgramaAcademicoReabierto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgramaAcademicoReabierto[]    findAll()
 * @method ProgramaAcademicoReabierto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramaAcademicoReabiertoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgramaAcademicoReabierto::class);
    }

    public function add(ProgramaAcademicoReabierto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ProgramaAcademicoReabierto $entity, bool $flush = true): ProgramaAcademicoReabierto
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ProgramaAcademicoReabierto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
