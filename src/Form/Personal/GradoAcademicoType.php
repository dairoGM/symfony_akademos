<?php

namespace App\Form\Personal;

use App\Entity\Personal\GradoAcademico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GradoAcademicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])->add('siglas', TextType::class, [
                'constraints' => [new Length(["min" => 3, 'minMessage' => 'El número mínimo de caracteres es {{ limit }}', "max" => 8, 'maxMessage' => 'El número máximo de caracteres es {{ limit }}']), new NotBlank()]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GradoAcademico::class,
        ]);
    }
}
