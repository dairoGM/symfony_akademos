<?php

namespace App\Repository;

use App\Entity\NotificacionesUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificacionesUsuario>
 *
 * @method NotificacionesUsuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificacionesUsuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificacionesUsuario[]    findAll()
 * @method NotificacionesUsuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificacionesUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificacionesUsuario::class);
    }

    public function add(NotificacionesUsuario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NotificacionesUsuario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NotificacionesUsuario[] Returns an array of NotificacionesUsuario objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NotificacionesUsuario
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
