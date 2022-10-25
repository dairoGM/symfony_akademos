<?php

namespace App\Repository\Traza;

use App\Entity\Traza\Traza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Traza>
 *
 * @method Traza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traza[]    findAll()
 * @method Traza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrazaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traza::class);
    }

    public function add(Traza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Traza $entity, bool $flush = true): Traza
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Traza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getExportarListado()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select("qb.id, 
            DateFormat(qb.creado, 'DD/MM/YYYY') as creado,
            qb.ip, qb.navegador,
            qb.sistemaOperativo,
            qb.data,
            tt.nombre as tipoTraza, 
            a.nombre as accion, 
            o.nombre as objeto, 
            p.primerNombre, 
            p.segundoNombre, 
            p.primerApellido, 
            p.segundoApellido")
            ->innerJoin('qb.tipoTraza', 'tt')
            ->innerJoin('qb.accion', 'a')
            ->innerJoin('qb.objeto', 'o')
            ->innerJoin('qb.persona', 'p');
        $qb->orderBy('qb.creado', 'desc');
        $resul = $qb->getQuery()->getResult();
        $final = [];
        foreach ($resul as $value) {
            $value['nombreCompleto'] = $value['primerNombre'] . ' ' . $value['segundoNombre'] . ' ' . $value['primerApellido'] . ' ' . $value['segundoApellido'];
            $final[] = $value;
        }
        return $final;
    }
}
