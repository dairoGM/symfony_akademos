<?php

namespace App\Repository\Personal;

use App\Entity\Personal\Persona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Persona>
 *
 * @method Persona|null find($id, $lockMode = null, $lockVersion = null)
 * @method Persona|null findOneBy(array $criteria, array $orderBy = null)
 * @method Persona[]    findAll()
 * @method Persona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Persona::class);
    }

    public function add(Persona $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function edit(Persona $entity, bool $flush = true): Persona
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function remove(Persona $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPersonasNoAsignadas($idActividad)
    {
        $qb = $this->createQueryBuilder('qb');
        $subQuery = $this->getEntityManager()->getRepository('App\Entity\PlanActividades\ActividadResponsable')->createQueryBuilder('subQb')
            ->select('p.id')
            ->innerJoin('subQb.persona', 'p')
            ->innerJoin('subQb.actividad', 'e')
            ->andWhere("e.id = $idActividad");
        $exp = $qb->expr()->notIn('qb.id', $subQuery->getDQL());
        $qb->andWhere($exp);
        $qb->orderBy('qb.primerNombre');
        $resul = $qb->getQuery()->getResult();

        return $resul;
    }


    public function getPersonasAsignadas($idActividad)
    {
        $qb = $this->createQueryBuilder('qb');
        $subQuery = $this->getEntityManager()->getRepository('App\Entity\PlanActividades\ActividadResponsable')->createQueryBuilder('subQb')
            ->select('p.id')
            ->innerJoin('subQb.persona', 'p')
            ->innerJoin('subQb.actividad', 'e')
            ->andWhere("e.id = $idActividad");
        $exp = $qb->expr()->in('qb.id', $subQuery->getDQL());
        $qb->andWhere($exp);
        $qb->orderBy('qb.primerNombre');
        $resul = $qb->getQuery()->getResult();

        return $resul;
    }

    public function getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdPersons)
    {
        $qb = $this->createQueryBuilder('qb');
        if (count($arrayIdPersons) > 0) {
            $qb->andWhere('qb.id NOT IN (:string)');
            $qb->setParameter('string', $arrayIdPersons, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
        }
//        $subQuery = $this->getEntityManager()->getRepository('App\Entity\Postgrado\MiembrosCopep')->createQueryBuilder('subQb')
//            ->select('p.id')
//            ->innerJoin('subQb.miembro', 'p')
//            ->innerJoin('subQb.copep', 'e')
//            ->andWhere("e.activo = true");
//        $exp = $qb->expr()->in('qb.id', $subQuery->getDQL());
//        $qb->andWhere($exp);

        $qb->orderBy('qb.primerNombre');
        $resul = $qb->getQuery()->getResult();

        return $resul;
    }

    public function gePersonasDadoArrayEstructuras($estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb');
//        if (is_array($estructurasNegocio) && count($estructurasNegocio) > 0) {
//            $qb->where("qb.activo = true and  qb.estructura IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
//        }
        $qb->orderBy('qb.primerNombre');
        $resul = $qb->getQuery()->getResult();
        return $resul;
    }

    public function getExportarListado($estructurasNegocio)
    {
        $qb = $this->createQueryBuilder('qb')
            ->select('qb.id, qb.primerNombre, qb.segundoNombre, qb.primerApellido, qb.segundoApellido, qb.activo, qb.carnetIdentidad')
            ->where("qb.activo = true and  qb.estructura IN(:valuesItems)")->setParameter('valuesItems', array_values($estructurasNegocio));
        $qb->orderBy('qb.id', 'desc');
        $resul = $qb->getQuery()->getResult();
        $final = [];
        foreach ($resul as $value) {
            $value['nombreCompleto'] = $value['primerNombre'] . ' ' . $value['segundoNombre'] . ' ' . $value['primerApellido'] . ' ' . $value['segundoApellido'];
            $final[] = $value;
        }
        return $final;
    }
}
