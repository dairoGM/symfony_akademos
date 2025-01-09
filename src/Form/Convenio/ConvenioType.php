<?php

namespace App\Form\Convenio;

use App\Entity\Convenio\Convenio;
use App\Entity\Convenio\Modalidad;
use App\Entity\Convenio\Tipo;
use App\Entity\Estructura\Pais;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\InstitucionExtranjera;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConvenioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('modalidad', EntityType::class, [
                'class' => Modalidad::class,
                'label' => 'Modalidad',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipo', EntityType::class, [
                'class' => Tipo::class,
                'label' => 'Tipo',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('documento', FileType::class, [
                'label' => 'Documento',
                'mapped' => false,
                'required' => false,
            ])
            ->add('institucionExtranjera', EntityType::class, [
                'class' => InstitucionExtranjera::class,
                'label' => 'Institución extranjera',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('institucionCubana', EntityType::class, [
                'class' => Institucion::class,
                'label' => 'Institución cubana',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('pais', EntityType::class, [
                'class' => Pais::class,
                'label' => 'País',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
//            ->add('cantidadAcciones', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
//                'label' => 'Cantidad de acciones ejecutadas en el año',
//                'required' => false,
//                'attr' => [
//                    'min' => 1
//                ]
//            ])
            ->add('fechaSuscribe', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('fechaCaducidad', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Convenio::class,
        ]);
    }


}
