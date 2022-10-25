<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Entidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entidad>
 *
 * @method Entidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entidad[]    findAll()
 * @method Entidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entidad::class);
    }

    public function add(Entidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Entidad $entity, bool $flush = true): Entidad
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Entidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getExportarListado()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.activo, c.nombre as tipo')
            ->innerJoin('qb.tipoEntidad', 'c')
            ->where("qb.activo = true");
        $qb->orderBy('qb.id', 'desc');
        $resul = $qb->getQuery()->getResult();
        $final = [];
        foreach ($resul as $value) {
            $value['activo'] = $value ? 'Habilitado' : 'Deshabilitado';
            $final[] = $value;
        }
        return $final;
    }
}
