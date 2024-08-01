<?php

namespace App\Repository\Informatizacion;

use App\Entity\Informatizacion\ServicioContratado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServicioContratado>
 *
 * @method ServicioContratado|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServicioContratado|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServicioContratado[]    findAll()
 * @method ServicioContratado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicioContratadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServicioContratado::class);
    }

    public function add(ServicioContratado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ServicioContratado $entity, bool $flush = true): ServicioContratado
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ServicioContratado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
