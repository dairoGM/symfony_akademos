<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudProgramaAcademicoComisionNacional>
 *
 * @method SolicitudProgramaAcademicoComisionNacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudProgramaAcademicoComisionNacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudProgramaAcademicoComisionNacional[]    findAll()
 * @method SolicitudProgramaAcademicoComisionNacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudProgramaAcademicoComisionNacionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudProgramaAcademicoComisionNacional::class);
    }

    public function add(SolicitudProgramaAcademicoComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SolicitudProgramaAcademicoComisionNacional $entity, bool $flush = true): SolicitudProgramaAcademicoComisionNacional
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SolicitudProgramaAcademicoComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
