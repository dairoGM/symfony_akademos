<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionCentrosEstudios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionCentrosEstudios>
 *
 * @method InstitucionCentrosEstudios|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionCentrosEstudios|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionCentrosEstudios[]    findAll()
 * @method InstitucionCentrosEstudios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionCentrosEstudiosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionCentrosEstudios::class);
    }

    public function add(InstitucionCentrosEstudios $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionCentrosEstudios $entity, bool $flush = true): InstitucionCentrosEstudios
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionCentrosEstudios $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
