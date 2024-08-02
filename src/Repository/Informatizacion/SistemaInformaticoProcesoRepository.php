<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\SistemaInformaticoProceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SistemaInformaticoProceso>
 *
 * @method SistemaInformaticoProceso|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaInformaticoProceso|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaInformaticoProceso[]    findAll()
 * @method SistemaInformaticoProceso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaInformaticoProcesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaInformaticoProceso::class);
    }

    public function add(SistemaInformaticoProceso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(SistemaInformaticoProceso $entity, bool $flush = true): SistemaInformaticoProceso
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(SistemaInformaticoProceso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
