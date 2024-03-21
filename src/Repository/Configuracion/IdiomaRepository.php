<?php

namespace App\Repository\Configuracion;

use App\Entity\Configuracion\Idioma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Idioma>
 *
 * @method Idioma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Idioma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Idioma[]    findAll()
 * @method Idioma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdiomaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Idioma::class);
    }

    public function add(Idioma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Idioma $entity, bool $flush = true): Idioma
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Idioma $entity, bool $flush = false): void
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
            ->innerJoin('qb.tipoIdioma', 'c')
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
