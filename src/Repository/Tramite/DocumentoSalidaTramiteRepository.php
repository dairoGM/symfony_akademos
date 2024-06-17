<?php

namespace App\Repository\Tramite;

use App\Entity\Tramite\DocumentoSalidaTramite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentoSalidaTramite>
 *
 * @method DocumentoSalidaTramite|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentoSalidaTramite|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentoSalidaTramite[]    findAll()
 * @method DocumentoSalidaTramite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentoSalidaTramiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentoSalidaTramite::class);
    }

    public function add(DocumentoSalidaTramite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(DocumentoSalidaTramite $entity, bool $flush = true): DocumentoSalidaTramite
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(DocumentoSalidaTramite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
