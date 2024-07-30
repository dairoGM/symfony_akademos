<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\SistemaInformatico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SistemaInformatico>
 *
 * @method SistemaInformatico|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaInformatico|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaInformatico[]    findAll()
 * @method SistemaInformatico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaInformaticoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaInformatico::class);
    }

    public function add(SistemaInformatico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SistemaInformatico $entity, bool $flush = true): SistemaInformatico
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SistemaInformatico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
