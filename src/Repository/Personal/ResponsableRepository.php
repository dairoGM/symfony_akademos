<?php

namespace App\Repository\Personal;

use App\Entity\Personal\Responsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Responsable>
 *
 * @method Responsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Responsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Responsable[]    findAll()
 * @method Responsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponsableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Responsable::class);
    }

    public function add(Responsable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Responsable $entity, bool $flush = true): Responsable
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Responsable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function geResponsablesDadoArrayEstructuras($estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->join('qb.persona', 'p')
            ->where("qb.activo = true and p.activo=true and p.estructura IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('p.primerNombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getExportarListado($estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('qb.id, qb.primerNombre, qb.segundoNombre, qb.primerApellido, qb.segundoApellido, qb.activo, qb.carnetIdentidad')
            ->join('r.persona', 'qb')
            ->where("qb.activo = true and r.activo=true and qb.estructura IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('qb.id', 'desc');
        $resul = $qb->getQuery()->getResult();
        $final = [];
        foreach ($resul as $value) {
            $value['nombreCompleto'] = $value['primerNombre'] . ' ' . $value['segundoNombre'] . ' ' . $value['primerApellido'] . ' ' . $value['segundoApellido'];
            $final[] = $value;
        }
        return $final;
    }
}
