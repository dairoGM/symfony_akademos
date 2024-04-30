<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\FichaSalidaEstado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichaSalidaEstado>
 *
 * @method FichaSalidaEstado|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichaSalidaEstado|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichaSalidaEstado[]    findAll()
 * @method FichaSalidaEstado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichaSalidaEstadoEstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichaSalidaEstado::class);
    }

    public function add(FichaSalidaEstado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(FichaSalidaEstado $entity, bool $flush = true): FichaSalidaEstado
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(FichaSalidaEstado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
