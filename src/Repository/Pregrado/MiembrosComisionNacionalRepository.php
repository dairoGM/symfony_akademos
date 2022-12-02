<?php

namespace App\Repository\Pregrado;

use App\Entity\Pregrado\MiembrosComisionNacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MiembrosComisionNacional>
 *
 * @method MiembrosComisionNacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method MiembrosComisionNacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method MiembrosComisionNacional[]    findAll()
 * @method MiembrosComisionNacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiembrosComisionNacionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MiembrosComisionNacional::class);
    }

    public function add(MiembrosComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(MiembrosComisionNacional $entity, bool $flush = true): MiembrosComisionNacional
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(MiembrosComisionNacional $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
