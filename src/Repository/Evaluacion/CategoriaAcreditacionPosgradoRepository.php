<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\CategoriaAcreditacionPosgrado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaAcreditacionPosgrado>
 *
 * @method CategoriaAcreditacionPosgrado|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaAcreditacionPosgrado|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaAcreditacionPosgrado[]    findAll()
 * @method CategoriaAcreditacionPosgrado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaAcreditacionPosgradoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaAcreditacionPosgrado::class);
    }

    public function add(CategoriaAcreditacionPosgrado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaAcreditacionPosgrado $entity, bool $flush = true): CategoriaAcreditacionPosgrado
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaAcreditacionPosgrado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSolicitudProgramaPosgradoCategoriaAcreditacion()
    {
        $qb = $this->createQueryBuilder('qb1')
            ->select(
                "qb.id, 
                        concat('(',qb.siglas,') ', qb.nombre) as nombre_siglas, 
                        ca.nombre as catAcreditacion,
                          DateFormat(qb1.fechaEmision, 'DD/MM/YYYY') as fechaEmision,
                         qb1.numeroPleno,
                         qb1.numeroAcuerdoPleno,
                         qb1.annosVigenciaCategoriaAcreditacion");

        $qb->leftJoin('qb1.institucion', 'qb');
        $qb->leftJoin('qb.gradoAcademicoRector', 'gradoAcademicoR');
        $qb->leftJoin('qb1.categoriaAcreditacion', 'ca');
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }
}
