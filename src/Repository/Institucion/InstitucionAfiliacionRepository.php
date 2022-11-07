<?php

namespace App\Repository\InstitucionAfiliacion;

use App\Entity\InstitucionAfiliacion\InstitucionAfiliacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionAfiliacion>
 *
 * @method InstitucionAfiliacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionAfiliacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionAfiliacion[]    findAll()
 * @method InstitucionAfiliacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionAfiliacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionAfiliacion::class);
    }

    public function add(InstitucionAfiliacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionAfiliacion $entity, bool $flush = true): InstitucionAfiliacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionAfiliacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
