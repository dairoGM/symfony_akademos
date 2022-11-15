<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionProgramaColaboracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionProgramaColaboracion>
 *
 * @method InstitucionProgramaColaboracion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionProgramaColaboracion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionProgramaColaboracion[]    findAll()
 * @method InstitucionProgramaColaboracion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionProgramaColaboracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionProgramaColaboracion::class);
    }

    public function add(InstitucionProgramaColaboracion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionProgramaColaboracion $entity, bool $flush = true): InstitucionProgramaColaboracion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionProgramaColaboracion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
