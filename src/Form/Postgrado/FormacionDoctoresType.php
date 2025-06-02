<?php

namespace App\Form\Postgrado;

use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\Estructura;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormacionDoctoresType extends AbstractType
{
    private $idProvincia;
    private $estructuraNegocio;

    public function __construct()
    {
        $this->idProvincia = null;
        $this->estructuraNegocio = [];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idProvincia = $options['data_choices'];
        $this->estructuraNegocio = $options['estructuraNegocio'];

        $builder
            ->add('categoriaEstructura', EntityType::class, [
                'class' => CategoriaEstructura::class,
                'label' => 'Categoría de estructura',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.activo = true')
                        ->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'required' => false,
                'mapped' => false // Esto indica que no está mapeado a ninguna propiedad
            ])
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'label' => 'Estructura',
                'required' => true,
                'choice_label' => function (Estructura $estructura) {
                    $siglasPadre = $estructura->getEstructura() ? $estructura->getEstructura()->getSiglas() : null;
                    return $siglasPadre ? sprintf('(%s) %s', $siglasPadre, $estructura->getNombre()) : $estructura->getNombre();
                },
                'query_builder' => function (EntityRepository $er) {
                    $estructurasNegocio = $this->estructuraNegocio;

                    if (count($estructurasNegocio) > 0) {
                        $qb = $er->createQueryBuilder('e');
                        $qb->select('e.id')
                            ->where('e.activo = true and e.iafd = false and e.centroAutorizadoPosgrado = false')
                            ->andWhere($qb->expr()->in('e.id', ':parents'))
                            ->setParameter('parents', array_values($estructurasNegocio));

                        $idsPadres = array_column($qb->getQuery()->getScalarResult(), 'id');

                        $getAllChildrenIds = function ($parentIds) use ($er) {
                            $allChildren = [];
                            $currentLevel = $parentIds;

                            while (!empty($currentLevel)) {
                                $qb = $er->createQueryBuilder('e');
                                $children = $qb->select('e.id')
                                    ->where('e.activo = true and e.iafd = false and e.centroAutorizadoPosgrado = false')
                                    ->andWhere($qb->expr()->in('e.estructura', ':parents'))
                                    ->setParameter('parents', $currentLevel)
                                    ->getQuery()
                                    ->getScalarResult();

                                $currentLevel = array_column($children, 'id');
                                $allChildren = array_merge($allChildren, $currentLevel);
                            }

                            return $allChildren;
                        };

                        $idsHijos = $getAllChildrenIds($idsPadres);
                        $todasIds = array_unique(array_merge($idsPadres, $idsHijos));

                        return $er->createQueryBuilder('e')
                            ->where('e.activo = true and e.iafd = false and e.centroAutorizadoPosgrado = false')
                            ->andWhere('e.id IN(:allIds)')
                            ->setParameter('allIds', $todasIds)
                            ->orderBy('e.nombre', 'ASC');
                    }

                    return $er->createQueryBuilder('e')
                        ->where('e.activo = true  and e.iafd = false and e.centroAutorizadoPosgrado = false')
                        ->orderBy('e.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'mapped' => false // Esto indica que no está mapeado a ninguna propiedad
            ]) ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'data_choices' => [],
            'estructuraNegocio' => [],
        ]);
    }
}