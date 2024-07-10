<?php

namespace App\Form\Evaluacion;

use App\Entity\Evaluacion\Convocatoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConvocatoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => true,
            ])
//            ->add('carta', FileType::class, [
//                'label' => 'Carta',
//                'mapped' => false,
//                'required' => $options['carta'] == 'registrar',
//            ])
            ->add('fechaInicio', TextType::class, [
                'label' => 'Fecha de inicio',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('fechaFin', TextType::class, [
                'label' => 'Fecha de fin',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Convocatoria::class,
            'carta' => null,
        ]);


    }
}
