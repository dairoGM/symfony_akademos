<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\Convocatoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Convocatoria>
 *
 * @method Convocatoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Convocatoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Convocatoria[]    findAll()
 * @method Convocatoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConvocatoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Convocatoria::class);
    }

    public function add(Convocatoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Convocatoria $entity, bool $flush = true): Convocatoria
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Convocatoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
