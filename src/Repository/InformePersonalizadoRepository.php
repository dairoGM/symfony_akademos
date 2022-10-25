<?php

namespace App\Repository;

use App\Entity\InformePersonalizado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InformePersonalizado>
 *
 * @method InformePersonalizado|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformePersonalizado|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformePersonalizado[]    findAll()
 * @method InformePersonalizado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformePersonalizadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InformePersonalizado::class);
    }

    public function add(InformePersonalizado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InformePersonalizado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    /**
     * @return InformePersonalizado[] Returns an array of InformePersonalizado objects
     */
    public function obtenerTodosDadoUsuario($usuario): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.usuario = :val')
            ->setParameter('val', $usuario)
            ->orWhere('i.privado = false')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?InformePersonalizado
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
