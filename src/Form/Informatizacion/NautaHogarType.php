<?php

namespace App\Form\Informatizacion;

use App\Entity\Informatizacion\NautaHogar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NautaHogarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroTelefono', TextType::class, [
                'label' => 'Número de teléfono'
            ])
            ->add('direccionInstalacion', TextareaType::class, [
                'label' => 'Dirección de instalación del servicio'
            ])
            ->add('servicioContratado', TextType::class, [
                'label' => 'Servicio contratado'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NautaHogar::class,
        ]);
    }
}
