<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\ModificacionPlanEstudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModificacionPlanEstudio>
 *
 * @method ModificacionPlanEstudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModificacionPlanEstudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModificacionPlanEstudio[]    findAll()
 * @method ModificacionPlanEstudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModificacionPlanEstudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModificacionPlanEstudio::class);
    }

    public function add(ModificacionPlanEstudio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ModificacionPlanEstudio $entity, bool $flush = true): ModificacionPlanEstudio
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ModificacionPlanEstudio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
