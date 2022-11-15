<?php

namespace App\Form\Institucion;

use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\TipoInstitucion;
use App\Entity\Personal\GradoAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstitucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
//            ->add('descripcion', TextareaType::class, [
//                'label' => 'Descripción',
//                'required' => false,
//            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado'
            ])
            ->add('logo', FileType::class, [
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ])
            ->add('siglas', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('rector', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('organigrama', FileType::class, [
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ])
            ->add('fechaFundacion', TextType::class, [
                'label' => 'Fecha de fundación',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('lema', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('mision', TextType::class, [
                'label' => 'Misión',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('vision', TextType::class, [
                'label' => 'Visión',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('sitioWeb', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('correo', EmailType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('direccionSedePrincipal', EmailType::class, [
                'label' => 'Dirección (Sede principal)',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('coordenadasSedePrincipal', EmailType::class, [
                'label' => 'Coordenadas (Sede principal)',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('campusUniversitario', EmailType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('gradoAcademicoRector', EntityType::class, [
                'label' => 'Grado académico del rector',
                'class' => GradoAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoInstitucion', EntityType::class, [
                'label' => 'Tipo de intitución',
                'class' => TipoInstitucion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('categoriaAcreditacion', EntityType::class, [
                'label' => 'Categoría de acreditación',
                'class' => CategoriaAcreditacion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Institucion::class,
        ]);
    }
}
