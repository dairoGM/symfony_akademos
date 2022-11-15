<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionRedesSociales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionRedesSociales>
 *
 * @method InstitucionRedesSociales|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionRedesSociales|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionRedesSociales[]    findAll()
 * @method InstitucionRedesSociales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionRedesSocialesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionRedesSociales::class);
    }

    public function add(InstitucionRedesSociales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionRedesSociales $entity, bool $flush = true): InstitucionRedesSociales
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionRedesSociales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
