<?php

namespace App\Form\Estructura;

use App\Entity\Estructura\CategoriaEntidad;
use App\Entity\Estructura\Entidad;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\TipoEntidad;
use App\Repository\Estructura\CategoriaEntidadRepository;
use App\Repository\Estructura\MunicipioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EntidadType extends AbstractType
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
            ->add('email', EmailType::class, [
                'label' => 'Correo',
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('siglas', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'constraints' => [
                    new NotBlank()
                ],
                "attr" => [
                    "data-inputmask" => '"mask": "(999) 999-9999"',
                    "data-mask" => ''
                ]
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('codigo', TextType::class, [
                'label' => 'Código',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('tipoEntidad', EntityType::class, [
                'class' => TipoEntidad::class,
                'label' => 'Tipo de entidad',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('estructura', EntityType::class, [
                'label' => 'Subordinado a',
                'class' => Estructura::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
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
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entidad::class,
            'data_choices' => []
        ]);
    }
}
