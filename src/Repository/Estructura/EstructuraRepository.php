<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Estructura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estructura>
 *
 * @method Estructura|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estructura|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estructura[]    findAll()
 * @method Estructura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstructuraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estructura::class);
    }

    public function add(Estructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Estructura $entity, bool $flush = true): Estructura
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Estructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function geEstructurasDadoArrayEstructuras($categoriaEstructuraId, $estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.categoriaEstructura = $categoriaEstructuraId and qb.activo = true and  qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function geEstructurasDadoArrayEstructurasTemp($categoriaEstructuraId)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.categoriaEstructura = $categoriaEstructuraId and qb.activo = true");
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getEstructurasDadoPeriodoPlanificado($periodo, $estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $subQuery = $this->getEntityManager()->getRepository('App\Entity\Planificacion\Plan')->createQueryBuilder('subQb')
            ->select('e.id')
            ->innerJoin('subQb.estructura', 'e')
            ->andWhere("subQb.periodo = '$periodo'");
        $exp = $qb->expr()->notIn('qb.id', $subQuery->getDQL());
        $qb->andWhere($exp);
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getExportarListado()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.activo, c.nombre as categoria, ep.siglas as siglasPadre')
            ->innerJoin('qb.categoriaEstructura', 'c')
            ->leftJoin('qb.estructura', 'ep')
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


    public function getEstructurasDadoIdCategoria($categoriaEstructuraId)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.siglas, ep.siglas as siglasPadre')
            ->leftJoin('qb.estructura', 'ep')
            ->where("qb.categoriaEstructura = $categoriaEstructuraId and qb.activo = true");
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }


    public function geEstructuras($estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->leftJoin('qb.estructura', 'ep')
            ->where("qb.activo = true and qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
