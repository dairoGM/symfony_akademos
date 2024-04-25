<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\InstitucionExtranjera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionExtranjera>
 *
 * @method InstitucionExtranjera|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionExtranjera|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionExtranjera[]    findAll()
 * @method InstitucionExtranjera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionExtranjeraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionExtranjera::class);
    }

    public function add(InstitucionExtranjera $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionExtranjera $entity, bool $flush = true): InstitucionExtranjera
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionExtranjera $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getInstitucionExtranjeraes()
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
                        ca.nombre as catAcreditacion,                     
                        concat(gradoAcademicoR.siglas,' ', qb.rector) as rector");
        $qb->innerJoin('qb.gradoAcademicoRector', 'gradoAcademicoR');
        $qb->leftJoin('qb.categoriaAcreditacion', 'ca');
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
