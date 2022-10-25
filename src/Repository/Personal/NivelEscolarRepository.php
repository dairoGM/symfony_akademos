<?php

namespace App\Repository\Personal;

use App\Entity\Personal\NivelEscolar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NivelEscolar>
 *
 * @method NivelEscolar|null find($id, $lockMode = null, $lockVersion = null)
 * @method NivelEscolar|null findOneBy(array $criteria, array $orderBy = null)
 * @method NivelEscolar[]    findAll()
 * @method NivelEscolar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelEscolarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NivelEscolar::class);
    }

    public function add(NivelEscolar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(NivelEscolar $entity, bool $flush = true): NivelEscolar
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(NivelEscolar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
