<?php

namespace App\Form\Estructura;

use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\TipoEstructura;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use Doctrine\DBAL\Types\TimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EstructuraType extends AbstractType
{
    private $idProvincia;

    public function __construct()
    {
        $this->idProvincia = null;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idProvincia = $options['data_choices'];
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('siglas', TextType::class, [
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('fechaActivacion', TextType::class, [
                'label' => 'Fecha de activación',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ],
                'mapped'=>false,
                'attr' => [
                    'class' => 'date-time-picker'
                ],
            ])
            ->add('tipoEstructura', EntityType::class, [
                'class' => TipoEstructura::class,
                'label' => 'Tipo de estructura',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'label' => 'Subordinado a',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('categoriaEstructura', EntityType::class, [
                'class' => CategoriaEstructura::class,
                'label' => 'Categoría de estructura',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado',
            ])
            ->add('ubicacion', TextType::class, [
                'label' => 'Ubicación',
                'required' => false
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ],
                "attr" => [
                    "data-inputmask" => '"mask": "(999) 999-9999"',
                    "data-mask" => ''
                ]
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('provincia', EntityType::class, [
                'class' => Provincia::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('municipio', EntityType::class, [
                'class' => Municipio::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $pro = $this->idProvincia;
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.provincia = '$pro' ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estructura::class,
            'data_choices' => [],
            'action' => []
        ]);
    }
}
