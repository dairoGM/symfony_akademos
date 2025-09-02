<?php

namespace App\Form\RRHH;


use App\Entity\Estructura\Estructura;
use App\Entity\RRHH\Grupo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class GrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ingrese el nombre del grupo',
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese una descripción opcional',
                    'rows' => 3
                ]
            ])
            ->add('activo', CheckboxType::class, [
                'label' => 'Habilitado',
                'required' => false,
            ])
            ->add('estructuras', EntityType::class, [
                'label' => 'Estructuras del Grupo',
                'class' => Estructura::class,
                'choice_label' => function (Estructura $estructura) {
                    $siglas = $estructura->getSiglas() ? '(' . $estructura->getSiglas() . ') ' : '';
                    return $siglas . $estructura->getNombre();
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'tom-select',
                    'data-placeholder' => 'Seleccione las estructuras',
                    'data-allow-clear' => 'true'
                ],
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('e')
                        ->leftJoin('e.categoriaEstructura', 'c')
                        ->leftJoin('e.estructura', 'ep')
                        ->addSelect('c')
                        ->addSelect('ep')
                        ->where('e.activo = :activo')
                        ->setParameter('activo', true)
                        ->orderBy('ep.nombre', 'ASC')
                        ->addOrderBy('e.nombre', 'ASC');
                },
                'group_by' => function (Estructura $estructura) {
                    $estructuraPadre = $estructura->getEstructura();
                    $categoria = $estructura->getCategoriaEstructura();
                    $categoriaNombre = $categoria ? $categoria->getNombre() : 'Sin categoría';

                    if ($estructuraPadre) {
                        return $estructuraPadre->getNombre() . ' - ' . $categoriaNombre;
                    }

                    return 'Estructuras Raíz - ' . $categoriaNombre;
                },
            ])
             ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grupo::class,
        ]);
    }
}