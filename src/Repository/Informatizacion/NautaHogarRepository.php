<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\NautaHogar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NautaHogar>
 *
 * @method NautaHogar|null find($id, $lockMode = null, $lockVersion = null)
 * @method NautaHogar|null findOneBy(array $criteria, array $orderBy = null)
 * @method NautaHogar[]    findAll()
 * @method NautaHogar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NautaHogarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NautaHogar::class);
    }

    public function add(NautaHogar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(NautaHogar $entity, bool $flush = true): NautaHogar
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(NautaHogar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
