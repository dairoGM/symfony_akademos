<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\Institucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Institucion>
 *
 * @method Institucion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Institucion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Institucion[]    findAll()
 * @method Institucion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Institucion::class);
    }

    public function add(Institucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Institucion $entity, bool $flush = true): Institucion
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Institucion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getInstituciones()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select(
                "qb.id, 
                        concat('(',qb.siglas,') ', qb.nombre) as nombre_siglas, 
                        qb.nombre, 
                        qb.logo,
                        qb.siglas,
                        qb.activo,
                        qb.codigo,                      
                        concat(gradoAcademicoR.siglas,' ', qb.rector) as rector");
        $qb->innerJoin('qb.gradoAcademicoRector', 'gradoAcademicoR');
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
