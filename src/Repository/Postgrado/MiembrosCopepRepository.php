<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\MiembrosCopep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MiembrosCopep>
 *
 * @method MiembrosCopep|null find($id, $lockMode = null, $lockVersion = null)
 * @method MiembrosCopep|null findOneBy(array $criteria, array $orderBy = null)
 * @method MiembrosCopep[]    findAll()
 * @method MiembrosCopep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiembrosCopepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MiembrosCopep::class);
    }

    public function add(MiembrosCopep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(MiembrosCopep $entity, bool $flush = true): MiembrosCopep
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(MiembrosCopep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getMiembros()
    {
        $qb = $this->createQueryBuilder('qb')
            ->innerJoin('qb.copep', 'c')
            ->innerJoin('qb.miembro', 'm')
            ->innerJoin('qb.rolComision', 'rc')
            ->where("c.activo = true");

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getPresidenteSecretario($rolPresindeteSecretario)
    {
        $qb = $this->createQueryBuilder('qb')
            ->innerJoin('qb.copep', 'c')
            ->innerJoin('qb.miembro', 'm')
            ->innerJoin('qb.rolComision', 'rc')
            ->where("c.activo = true and  qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($rolPresindeteSecretario));

        $resul = $qb->getQuery()->getResult();
        return $resul;
    }


}
