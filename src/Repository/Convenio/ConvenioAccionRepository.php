<?php

namespace App\Repository\Convenio;

use App\Entity\Convenio\ConvenioAccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConvenioAccion>
 *
 * @method ConvenioAccion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConvenioAccion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConvenioAccion[]    findAll()
 * @method ConvenioAccion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConvenioAccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConvenioAccion::class);
    }

    public function add(ConvenioAccion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ConvenioAccion $entity, bool $flush = true): ConvenioAccion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ConvenioAccion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
