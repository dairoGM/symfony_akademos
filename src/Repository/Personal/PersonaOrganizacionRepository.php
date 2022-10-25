<?php

namespace App\Repository\Personal;

use App\Entity\Personal\PersonaOrganizacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonaOrganizacion>
 *
 * @method PersonaOrganizacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonaOrganizacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonaOrganizacion[]    findAll()
 * @method PersonaOrganizacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaOrganizacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonaOrganizacion::class);
    }

    public function add(PersonaOrganizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(PersonaOrganizacion $entity, bool $flush = true): PersonaOrganizacion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(PersonaOrganizacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
