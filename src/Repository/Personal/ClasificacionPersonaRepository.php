<?php

namespace App\Repository\Personal;

use App\Entity\Personal\ClasificacionPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClasificacionPersona>
 *
 * @method ClasificacionPersona|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClasificacionPersona|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClasificacionPersona[]    findAll()
 * @method ClasificacionPersona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasificacionPersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClasificacionPersona::class);
    }

    public function add(ClasificacionPersona $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ClasificacionPersona $entity, bool $flush = true): ClasificacionPersona
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ClasificacionPersona $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
