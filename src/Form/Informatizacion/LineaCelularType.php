<?php

namespace App\Form\Informatizacion;

use App\Entity\Informatizacion\LineaCelular;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LineaCelularType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroTelefono', TextType::class, [
                'label' => 'Número de teléfono',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('pin', TextType::class, [
                'required' => false,
            ])->add('puk', TextType::class, [
                'required' => false,
            ])
            ->add('planVoz', TextType::class, [
                'required' => false,
            ])
            ->add('planSms', TextType::class, [
                'required' => false,
            ])
            ->add('planDatos', TextType::class, [
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LineaCelular::class,
        ]);
    }
}
