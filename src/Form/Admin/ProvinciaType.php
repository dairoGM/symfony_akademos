<?php

namespace App\Form\Admin;

use App\Entity\Estructura\Provincia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProvinciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('activo', CheckboxType::class, [
                'label' => 'Activo',
                'required' => false,
                'label' => 'Habilitado',
            ])
            ->add('codigo', IntegerType::class, [
                'label' => 'Código',
                'required' => true,
                'constraints' => [new Length(["min" => 2, 'minMessage' => 'El número mínimo de caracteres es {{ limit }}', "max" => 2, 'maxMessage' => 'El número máximo de caracteres es {{ limit }}']), new NotBlank()]
            ])
            ->add('siglas', TextType::class, [
                'label' => 'Siglas',
                'required' => true,
                'constraints' => [new Length(["max" => 3, 'maxMessage' => 'El número maximo de caracteres es {{ limit }}']), new NotBlank()]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Provincia::class,
        ]);
    }
}
