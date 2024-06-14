<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\DocumentoSalida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentoSalida>
 *
 * @method DocumentoSalida|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentoSalida|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentoSalida[]    findAll()
 * @method DocumentoSalida[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentoSalidaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentoSalida::class);
    }

    public function add(DocumentoSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(DocumentoSalida $entity, bool $flush = true): DocumentoSalida
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(DocumentoSalida $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
