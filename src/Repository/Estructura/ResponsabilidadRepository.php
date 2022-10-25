<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Responsabilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\True_;

/**
 * @extends ServiceEntityRepository<Responsabilidad>
 *
 * @method Responsabilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Responsabilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Responsabilidad[]    findAll()
 * @method Responsabilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponsabilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Responsabilidad::class);
    }

    public function add(Responsabilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Responsabilidad $entity, bool $flush = true): Responsabilidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Responsabilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQueryBuilderForm($idEstructura)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id, r.nombre');
        $subQuery = $this->getEntityManager()->getRepository('App\Entity\Estructura\Plaza')->createQueryBuilder('qb')
            ->select('res.id')
            ->innerJoin('qb.responsabilidad', 'res')
            ->innerJoin('qb.estructura', 'e')
            ->andWhere("e.id = $idEstructura");
        $exp = $qb->expr()->in('r.id', $subQuery->getDQL());
        $qb->andWhere($exp);
        $qb->orderBy('r.nombre');
        $resul = $qb->getQuery()->getResult();
        $return = [];
        if (is_array($resul)) {
            foreach ($resul as $value) {
                $return[$value['nombre']] = $value['id'];
            }
        }
        return $return;
    }
}
