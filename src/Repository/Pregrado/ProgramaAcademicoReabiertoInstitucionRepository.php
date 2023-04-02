<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\ProgramaAcademicoReabiertoInstitucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgramaAcademicoReabiertoInstitucion>
 *
 * @method ProgramaAcademicoReabiertoInstitucion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgramaAcademicoReabiertoInstitucion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgramaAcademicoReabiertoInstitucion[]    findAll()
 * @method ProgramaAcademicoReabiertoInstitucion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramaAcademicoReabiertoInstitucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgramaAcademicoReabiertoInstitucion::class);
    }

    public function add(ProgramaAcademicoReabiertoInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ProgramaAcademicoReabiertoInstitucion $entity, bool $flush = true): ProgramaAcademicoReabiertoInstitucion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ProgramaAcademicoReabiertoInstitucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
