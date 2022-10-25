<?php

namespace App\Repository\Traza;

use App\Entity\Traza\ConfiguracionTraza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfiguracionTraza>
 *
 * @method ConfiguracionTraza|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfiguracionTraza|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfiguracionTraza[]    findAll()
 * @method ConfiguracionTraza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfiguracionTrazaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfiguracionTraza::class);
    }

    public function add(ConfiguracionTraza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(ConfiguracionTraza $entity, bool $flush = true): ConfiguracionTraza
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(ConfiguracionTraza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
