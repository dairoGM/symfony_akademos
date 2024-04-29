<?php

namespace App\Repository\Convenio;

use App\Entity\Convenio\Modalidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Modalidad>
 *
 * @method Modalidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modalidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modalidad[]    findAll()
 * @method Modalidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModalidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modalidad::class);
    }

    public function add(Modalidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Modalidad $entity, bool $flush = true): Modalidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Modalidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
