<?php

namespace App\Form\Tramite;

use App\Entity\Economia\ConceptoGasto;
use App\Entity\Estructura\Pais;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Tramite\TipoPasaporte;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FichaSalidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'label' => 'País de destino',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('institucionExtranjera', EntityType::class, [
                'class' => InstitucionExtranjera::class,
                'required' => false,
                'label' => 'Institución extranjera',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('provinciaExtranjera', TextType::class, [
                'required' => false,
                'label' => 'Ciudad extranjera',
            ])
            ->add('conceptoSalida', EntityType::class, [
                'class' => ConceptoSalida::class,
                'label' => 'Concepto de salida',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('conceptoGasto', EntityType::class, [
                'class' => ConceptoGasto::class,
                'label' => 'Concepto de gasto',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false,
                'multiple' => true,
            ])
            ->add('Objetivo', TextareaType::class, [
                'required' => false,
            ])
            ->add('fechaSalidaPrevista', TextType::class, [
                'label' => 'Fecha de salida prevista',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('fechaRegresoPrevista', TextType::class, [
                'label' => 'Fecha de regreso prevista',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
//            ->add('fechaSalidaReal', TextType::class, [
//                'label' => 'Fecha de salida real',
//                'mapped' => false,
//                'required' => false,
//                'attr' => [
//                    'class' => 'date-time-picker'
//                ]
//            ])
//            ->add('fechaRegresoReal', TextType::class, [
//                'label' => 'Fecha de regreso real',
//                'mapped' => false,
//                'required' => false,
//                'attr' => [
//                    'class' => 'date-time-picker'
//                ]
//            ])
            ->add('tiempoEstancia', IntegerType::class, [
                'label' => 'Tiempo de estancia',
                'required' => false,
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('tipoPasaporte', EntityType::class, [
                'class' => TipoPasaporte::class,
                'label' => 'Tipo de pasaporte',
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('numeroPasaporte', TextType::class, [
                'label' => 'Número de pasaporte',
                'required' => false,
            ])
            ->add('fechaEmisionPasaporte', TextType::class, [
                'label' => 'Fecha de emisión del pasaporte',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('fechaCaducidadPasaporte', TextType::class, [
                'label' => 'Fecha de caducidad del pasaporte',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('cartaInvitacion', FileType::class, [
                'label' => 'Carta de invitación',
                'mapped' => false,
                'required' => false,
            ]);
//            ->add('requiereVisa', CheckboxType::class, [
//                'required' => false,
//                'label' => 'Requiere visa'
//            ])
//            ->add('aprobadoFactoresIes', CheckboxType::class, [
//                'required' => false,
//                'label' => 'Aprobado por los factores en la IES'
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichaSalida::class,
        ]);
    }
}
