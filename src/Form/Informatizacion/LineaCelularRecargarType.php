<?php

namespace App\Form\Informatizacion;

use App\Entity\Informatizacion\LineaCelular;
use App\Entity\Informatizacion\LineaCelularRecargas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LineaCelularRecargarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            'data_class' => LineaCelularRecargas::class,
        ]);
    }
}
