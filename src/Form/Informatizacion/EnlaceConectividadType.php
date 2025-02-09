<?php

namespace App\Form\Informatizacion;

use App\Entity\Convenio\Tipo;

;

use App\Entity\Estructura\Estructura;
use App\Entity\Informatizacion\EnlaceConectividad;
use App\Entity\Informatizacion\Marca;
use App\Entity\Informatizacion\TipoConectividad;
use App\Entity\Institucion\CategoriaAcreditacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EnlaceConectividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ed', TextType::class, [
                'label' => 'ED',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('unidadMedida', ChoiceType::class, [
                'label' => 'Unidad de medida',
                'choices' => ['Kb' => 'Kb', 'Mb' => 'Mb', 'Gb' => 'Gb', 'Tb' => 'Tb'],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ],
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoConexion', ChoiceType::class, [
                'label' => 'Tipo de conexión',
                'choices' => ['FO' => 'FO', 'ADSL' => 'ADSL', 'FR' => 'FR'],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ],
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('anchoBanda', TextType::class, [
                'label' => 'Ancho de banda',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('precio', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('tipoConectividad', EntityType::class, [
                'label' => 'Alcance',
                'class' => TipoConectividad::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('estructura', EntityType::class, [
                'label' => 'Institución',
                'class' => Estructura::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.esEntidad = true')
                        ->orderBy('e.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EnlaceConectividad::class,
        ]);
    }
}
