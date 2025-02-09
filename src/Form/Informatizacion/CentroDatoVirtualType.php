<?php

namespace App\Form\Informatizacion;

use App\Entity\Convenio\Tipo;

;

use App\Entity\Estructura\Estructura;
use App\Entity\Informatizacion\CentroDatoVirtual;
use App\Entity\Informatizacion\EnlaceConectividad;
use App\Entity\Informatizacion\Marca;
use App\Entity\Informatizacion\TipoConectividad;
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

class CentroDatoVirtualType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del CDV',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('ram', TextType::class, [
                'label' => 'RAM ',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('cpu', TextType::class, [
                'label' => 'CPU',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('hdd', TextType::class, [
                'label' => 'HDD ',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('hddSalva', TextType::class, [
                'label' => 'HDD Salva ',
                'required' => false,
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('cantidadIpReales', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('precio', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
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
            'data_class' => CentroDatoVirtual::class,
        ]);
    }
}
