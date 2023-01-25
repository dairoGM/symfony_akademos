<?php

namespace App\Repository\Institucion;

use App\Entity\Institucion\InstitucionRevistaCientifica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitucionRevistaCientifica>
 *
 * @method InstitucionRevistaCientifica|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitucionRevistaCientifica|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitucionRevistaCientifica[]    findAll()
 * @method InstitucionRevistaCientifica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitucionRevistaCientificaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitucionRevistaCientifica::class);
    }

    public function add(InstitucionRevistaCientifica $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(InstitucionRevistaCientifica $entity, bool $flush = true): InstitucionRevistaCientifica
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(InstitucionRevistaCientifica $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRevistasCiencificas()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('
                    qb.nombreRevista,
                    qb.indexadaEn,
                    qb.direccionElectronicaRevista,
                    qb.descripcionRevista,
                    v.nombre as visibilidad,
                    i.nombre as nombreInstitucion,
                    i.siglas as siglasIntitucion
                    ')
            ->innerJoin('qb.institucion', 'i')
            ->leftJoin('qb.visibilidad', 'v');
        $qb->orderBy('qb.nombreRevista');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

}
