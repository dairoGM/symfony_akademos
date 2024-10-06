<?php

namespace App\Form\Institucion;

use App\Entity\Estructura\Estructura;
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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstitucionType extends AbstractType
{
    private $idCategoriaEstructura;

    public function __construct()
    {
        $this->idCategoriaEstructura = null;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idCategoriaEstructura = $options['idCategoriaEstructura'];
        $builder
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $categoriaEstructura = $this->idCategoriaEstructura;
//                    return $er->createQueryBuilder('u')->where("u.activo = true and u.categoriaEstructura = '$categoriaEstructura' ")->orderBy('u.nombre', 'ASC');
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.esEntidad = true ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione'
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado'
            ])
            ->add('logo', FileType::class, [
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ])
            ->add('siglas', TextType::class, [
                'attr' => [
                    'readonly' => true
                ],
                'constraints' => [new Length(["min" => 2, 'minMessage' => 'El número mínimo de caracteres es {{ limit }}', "max" => 50, 'maxMessage' => 'El número máximo de caracteres es {{ limit }}']), new NotBlank()]
            ])
            ->add('codigo', TextType::class, [
                'label' => 'Código REUP',
                'constraints' => [new Length(["min" => 3, 'minMessage' => 'El número mínimo de caracteres es {{ limit }}', "max" => 20, 'maxMessage' => 'El número máximo de caracteres es {{ limit }}']), new NotBlank()]
            ])
            ->add('rector', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
//            ->add('organigrama', FileType::class, [
//                'mapped' => false,
//                'required' => $options['action'] == 'registrar',
//            ])
            ->add('fechaFundacion', TextType::class, [
                'label' => 'Fecha de fundación',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('lema', TextType::class, [
                'required' => false,
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
                'required' => false,
                "attr" => [
                    "data-inputmask" => '"mask": "(99) 9 999-99"',
                    "data-mask" => '',
                    'readonly' => true
                ]
            ])
            ->add('correo', EmailType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ], 'attr' => [
                    'readonly' => true
                ],
            ])
            ->add('direccionSedePrincipal', TextType::class, [
                'label' => 'Dirección (Sede principal)',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ], 'attr' => [
                    'readonly' => true
                ],
            ])
            ->add('coordenadasSedePrincipal', TextType::class, [
                'label' => 'Coordenadas (Sede principal)',
                'required' => false,
                'attr' => [
                    'readonly' => true
                ]
            ])
//            ->add('campusUniversitario', TextType::class, [
//                'constraints' => [
//                    new NotBlank([], 'Este valor no debe estar en blanco.')
//                ]
//            ])
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
                'required' => false,
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
//            ->add('categoriaAcreditacion', EntityType::class, [
//                'label' => 'Categoría de acreditación',
//                'class' => CategoriaAcreditacion::class,
//                'choice_label' => 'nombre',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
//                },
//                'placeholder' => 'Seleccione',
//                'empty_data' => null,
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Institucion::class,
            'idCategoriaEstructura' => [],
        ]);
    }
}
