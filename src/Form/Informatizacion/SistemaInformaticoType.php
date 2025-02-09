<?php

namespace App\Form\Informatizacion;

use App\Entity\Convenio\Tipo;

;

use App\Entity\Estructura\Estructura;
use App\Entity\Informatizacion\EnlaceConectividad;
use App\Entity\Informatizacion\Marca;
use App\Entity\Informatizacion\Proceso;
use App\Entity\Informatizacion\SistemaInformatico;
use App\Entity\Informatizacion\TipoConectividad;
use App\Entity\Informatizacion\TipoSistema;
use App\Entity\Informatizacion\Visibilidad;
use App\Entity\Institucion\CategoriaAcreditacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SistemaInformaticoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('direccionWeb', TextType::class, [
                'label' => 'Dirección web',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('proceso', EntityType::class, [
                'label' => 'Procesos que impacta',
                'class' => Proceso::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('desarrollador', TextType::class, [
                'required' => true,
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('visibilidad', EntityType::class, [
                'class' => Visibilidad::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoSistema', EntityType::class, [
                'label' => 'Tipo de sistema',
                'class' => TipoSistema::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'label' => 'Institución',
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
            'data_class' => SistemaInformatico::class,
        ]);
    }
}
