<?php

namespace App\Repository\RRHH;

use App\Entity\RRHH\AE2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AE2>
 *
 * @method AE2|null find($id, $lockMode = null, $lockVersion = null)
 * @method AE2|null findOneBy(array $criteria, array $orderBy = null)
 * @method AE2[]    findAll()
 * @method AE2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AE2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AE2::class);
    }

    public function add(AE2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(AE2 $entity, bool $flush = true): AE2
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(AE2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDistinctEntidades(?int $entidadId = null, ?int $mes = null, ?int $anno = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('DISTINCT e.id, e.nombre')
            ->join('a.entidad', 'e')
            ->orderBy('a.mes', 'asc');

        if ($entidadId !== null) {
            $qb->andWhere('e.id = :entidadId')
                ->setParameter('entidadId', $entidadId);
        }

        if ($mes !== null) {
            $qb->andWhere('a.mes = :mes')
                ->setParameter('mes', $mes);
        }

        if ($anno !== null) {
            $qb->andWhere('a.anno = :anno')
                ->setParameter('anno', $anno);
        }

        return $qb->orderBy('e.nombre', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function findByFilters(array $filters = [])
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.anno', 'ASC')
            ->addOrderBy('a.mes', 'ASC');

        if (!empty($filters['entidad'])) {
            $qb->andWhere('a.entidad = :entidad')
                ->setParameter('entidad', $filters['entidad']);
        }

        if (!empty($filters['mes'])) {
            $qb->andWhere('a.mes = :mes')
                ->setParameter('mes', $filters['mes']);
        }

        if (!empty($filters['anno'])) {
            $qb->andWhere('a.anno = :anno')
                ->setParameter('anno', $filters['anno']);
        }

        return $qb->getQuery()->getResult();
    }

    public function findFilteredAndTotalized(array $filters = [])
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a');

        // Aplicar filtros
        if (!empty($filters['entidad'])) {
            $qb->andWhere('a.entidad = :entidad')
                ->setParameter('entidad', $filters['entidad']);
        }

        if (!empty($filters['mes'])) {
            $qb->andWhere('a.mes = :mes')
                ->setParameter('mes', $filters['mes']);
        }

        if (!empty($filters['anno'])) {
            $qb->andWhere('a.anno = :anno')
                ->setParameter('anno', $filters['anno']);
        }

        // Obtener los registros filtrados
        $registros = $qb->getQuery()->getResult();

        // Calcular totales
        $totales = $this->calculateTotales($registros);

        return [
            'registros' => $registros,
            'totales' => $totales
        ];
    }

    private function calculateTotales(array $registros): array
    {
        $totales = [];

        if (empty($registros)) {
            return $totales;
        }

        // Campos numéricos que queremos totalizar
        $numericFields = [
            'totalPlantillaAprobada',
            'totalPlantillaCubierta',
            'totalGeneralContratos',
            'totalContratosProfesoresTiempoDeterminado',
            'profesoresTiempoCompleto',
            'totalContratosNoDocentes',
            'contratosNoDocentesConRespaldo',
            'contratosPorSustitucion',
            'periodoPrueba',
            'serenosAuxiliaresLimpieza',
            'laboresAgricolas',
            'jubilados',
            'otrosConRespaldo',
            'contratosNoDocentesSinRespaldo',
            'serenosAuxiliaresLimpiezaSinRespaldo',
            'laboresAgricolasSinRespaldo',
            'jubiladosSinRespaldo',
            'ejecucionObra',
            'otrosSinRespaldo',
            'reservaCientificaPreparacion',
            'recienGraduadosPreparacion',
            'reservaDireccionProvincialTrabajo',
            'tecnicosMediosPreparacion',
            'totalEstudiantesUniversidadContratados',
            'estudiantesAuxiliaresTecnicosDocencia',
            'estudiantesCargosNoDocentes'
        ];

        foreach ($numericFields as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists(AE2::class, $getter)) {
                $totales[$field] = array_reduce($registros, function ($carry, $item) use ($getter) {
                    return $carry + ($item->$getter() ?? 0);
                }, 0);
            }
        }

        return $totales;
    }

    public function getUniversidadesAE2()
    {
        $query = "SELECT distinct tbd_estructura.id, tbd_estructura.nombre 
                   FROM rrhh.tbd_ae2 
                   join estructura.tbd_estructura  on tbd_estructura.id = tbd_ae2.entidad_id
                     order by  tbd_estructura.nombre";

        $connect = $this->getEntityManager()->getConnection();
        $temp = $connect->executeQuery($query);
        $temp = $temp->fetchAllAssociative();
        return $temp;
    }
}
