<?php

namespace App\Form\Economia;

use App\Entity\Economia\ConceptoGasto;
use App\Entity\Economia\Obsequio;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Tramite\ConceptoSalida;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ObsequioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('marca', TextType::class, [
                'required' => false,
            ])
            ->add('modelo', TextType::class, [
                'required' => false,
            ])
            ->add('color', TextType::class, [
                'required' => false,
            ])
            ->add('talla', TextType::class, [
                'required' => false,
            ])
            ->add('presentacion', TextType::class, [
                'label' => 'Presentación',
                'required' => false,
            ])
            ->add('tipo', TextType::class, [
                'required' => false,
            ])
            ->add('genero', TextType::class, [
                'label' => 'Género',
                'required' => false,
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
            'data_class' => Obsequio::class,
        ]);
    }
}
