<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\CategoriaAcreditacionPregrado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriaAcreditacionPregrado>
 *
 * @method CategoriaAcreditacionPregrado|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaAcreditacionPregrado|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaAcreditacionPregrado[]    findAll()
 * @method CategoriaAcreditacionPregrado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaAcreditacionPregradoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaAcreditacionPregrado::class);
    }

    public function add(CategoriaAcreditacionPregrado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(CategoriaAcreditacionPregrado $entity, bool $flush = true): CategoriaAcreditacionPregrado
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(CategoriaAcreditacionPregrado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSolicitudProgramaPregradoCategoriaAcreditacion()
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
