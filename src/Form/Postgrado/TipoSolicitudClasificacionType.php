<?php

namespace App\Form\Postgrado;

use App\Entity\Postgrado\TipoPrograma;
use App\Entity\Postgrado\TipoSolicitud;
use App\Entity\Postgrado\TipoSolicitudClasificacion;
use App\Entity\Postgrado\TipoSolicitudNivel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TipoSolicitudClasificacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clasificacion', TextType::class, [
                'label' => 'Clasificación',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ]) ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoSolicitudClasificacion::class,
        ]);
    }
}
