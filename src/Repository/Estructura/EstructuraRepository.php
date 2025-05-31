<?php

namespace App\Repository\Estructura;

use App\Entity\Estructura\Estructura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estructura>
 *
 * @method Estructura|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estructura|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estructura[]    findAll()
 * @method Estructura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstructuraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estructura::class);
    }

    public function add(Estructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Estructura $entity, bool $flush = true): Estructura
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Estructura $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function geEstructurasDadoArrayEstructuras($categoriaEstructuraId, $estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.categoriaEstructura = $categoriaEstructuraId and qb.activo = true and  qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function geEstructurasDadoArrayEstructurasTemp($categoriaEstructuraId)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.categoriaEstructura = $categoriaEstructuraId and qb.activo = true");
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getEstructurasDadoPeriodoPlanificado($periodo, $estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->where("qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $subQuery = $this->getEntityManager()->getRepository('App\Entity\Planificacion\Plan')->createQueryBuilder('subQb')
            ->select('e.id')
            ->innerJoin('subQb.estructura', 'e')
            ->andWhere("subQb.periodo = '$periodo'");
        $exp = $qb->expr()->notIn('qb.id', $subQuery->getDQL());
        $qb->andWhere($exp);
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getExportarListado()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.activo, c.nombre as categoria, ep.siglas as siglasPadre')
            ->innerJoin('qb.categoriaEstructura', 'c')
            ->leftJoin('qb.estructura', 'ep')
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


    public function getExportarListadoDadoId($idEstructura)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.activo, c.nombre as categoria, ep.siglas as siglasPadre')
            ->innerJoin('qb.categoriaEstructura', 'c')
            ->leftJoin('qb.estructura', 'ep')
            ->where("qb.activo = true and ep.id = $idEstructura");
        $qb->orderBy('qb.id', 'desc');
        $resul = $qb->getQuery()->getResult();
        $final = [];
        foreach ($resul as $value) {
            $value['activo'] = $value ? 'Habilitado' : 'Deshabilitado';
            $final[] = $value;
        }
        return $final;
    }

    public function getEstructurasDadoIdCategoria($categoriaEstructuraId)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.siglas, ep.siglas as siglasPadre')
            ->leftJoin('qb.estructura', 'ep')
            ->where("qb.categoriaEstructura = $categoriaEstructuraId and qb.activo = true");
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }


    public function geEstructuras($estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->leftJoin('qb.estructura', 'ep')
            ->where("qb.activo = true and qb.id IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getEstructurasNegocios($estructurasNegocio): array
    {
        if (empty($estructurasNegocio)) {
            return [];
        }

        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection();

        // Usamos CTE recursivo para mejor performance
        $sql = "
        WITH RECURSIVE estructura_tree AS (
            SELECT id, nombre, estructura_id 
            FROM estructura.tbd_estructura 
            WHERE activo = true AND id IN (:parents)
            
            UNION ALL
            
            SELECT e.id, e.nombre, e.estructura_id
            FROM estructura.tbd_estructura e
            JOIN estructura_tree et ON e.estructura_id = et.id
            WHERE e.activo = true
        )
        SELECT e.* 
        FROM estructura.tbd_estructura e
        JOIN estructura_tree et ON e.id = et.id
        ORDER BY e.nombre
    ";

        $stmt = $connection->executeQuery($sql, ['parents' => $estructurasNegocio]);
        $result = $stmt->fetchAllAssociative();

        return $this->transformResultsToEntities($result);
    }

    private function transformResultsToEntities(array $results): array
    {
        $entities = [];
        foreach ($results as $row) {
            $entities[] = $this->find($row['id']);
        }
        return $entities;
    }




    public function getEstructurasDadoIdsCategoria()
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.nombre, qb.siglas, ep.siglas as siglasPadre')
            ->leftJoin('qb.estructura', 'ep')
            ->where("qb.categoriaEstructura in (5,6) and qb.activo = true");
        $qb->orderBy('qb.nombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getEstructuraDadoEntidad($id)
    {
        $query = "WITH RECURSIVE estructura_recursiva AS (
    -- Punto de partida (primer registro)
    SELECT tbd_estructura.id, concat('(', ee.siglas, ') ', tbd_estructura.nombre) as nombre
    FROM estructura.tbd_estructura
    LEFT JOIN estructura.tbd_estructura ee ON ee.id = tbd_estructura.estructura_id
    WHERE tbd_estructura.id = $id  -- Punto de partida
    UNION
    -- Recursión: avanzar por la estructura
    SELECT e.id, concat('(', ee.siglas, ') ', e.nombre) as nombre
    FROM estructura.tbd_estructura e
    LEFT JOIN estructura.tbd_estructura ee ON ee.id = e.estructura_id
    INNER JOIN estructura_recursiva er ON e.estructura_id = er.id
)
-- Excluir el primer registro (punto de partida)
SELECT * 
FROM estructura_recursiva
WHERE id != $id;
;";
        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        return $temp->fetchAllAssociative();

    }
}
