<?php

namespace App\Repository\Postgrado;

use App\Entity\Postgrado\TipoPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoPrograma>
 *
 * @method TipoPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoPrograma[]    findAll()
 * @method TipoPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoPrograma::class);
    }

    public function add(TipoPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(TipoPrograma $entity, bool $flush = true): TipoPrograma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(TipoPrograma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
