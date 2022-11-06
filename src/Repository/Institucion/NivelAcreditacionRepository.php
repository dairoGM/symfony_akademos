<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\NivelAcreditacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NivelAcreditacion>
 *
 * @method NivelAcreditacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method NivelAcreditacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method NivelAcreditacion[]    findAll()
 * @method NivelAcreditacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelAcreditacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NivelAcreditacion::class);
    }

    public function add(NivelAcreditacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(NivelAcreditacion $entity, bool $flush = true): NivelAcreditacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(NivelAcreditacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
