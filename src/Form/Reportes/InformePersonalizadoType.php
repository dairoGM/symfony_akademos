<?php

namespace App\Form\Reportes;

use App\Entity\InformePersonalizado;
use App\Entity\PlanActividades\TipoActividad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformePersonalizadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('privado', CheckboxType::class, [
                'required' => false,
                'label' => 'Privado'
            ])
            ->add('contenido', TextareaType::class, [
                'label' => 'Reporte',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InformePersonalizado::class,
        ]);
    }
}
