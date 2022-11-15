<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionMecanismoColaboracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionMecanismoColaboracion>
 *
 * @method InstitucionMecanismoColaboracion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionMecanismoColaboracion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionMecanismoColaboracion[]    findAll()
 * @method InstitucionMecanismoColaboracion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionMecanismoColaboracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionMecanismoColaboracion::class);
    }

    public function add(InstitucionMecanismoColaboracion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionMecanismoColaboracion $entity, bool $flush = true): InstitucionMecanismoColaboracion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionMecanismoColaboracion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
