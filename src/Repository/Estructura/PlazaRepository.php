<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Plaza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plaza>
 *
 * @method Plaza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plaza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plaza[]    findAll()
 * @method Plaza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlazaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plaza::class);
    }

    public function add(Plaza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Plaza $entity, bool $flush = true): Plaza
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Plaza $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getResponsabilidadesDadoIdEstructura($estructuraId)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('r.id, r.nombre')
            ->join('qb.estructura', 'e')
            ->join('qb.responsabilidad', 'r')
            ->where("e.id = $estructuraId and r.activo = true and e.activo=true");
        $qb->orderBy('r.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }


}
