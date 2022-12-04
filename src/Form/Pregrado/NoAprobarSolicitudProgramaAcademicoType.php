<?php

namespace App\Form\Pregrado;

use App\Entity\Pregrado\SolicitudProgramaAcademico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NoAprobarSolicitudProgramaAcademicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descripcionNoAprobacion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('dictamen', FileType::class, [
                'label' => 'Dictamen',
                'mapped' => false,
                'required' =>  $options['dictamen'] == 'registrar',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudProgramaAcademico::class,
            'dictamen'=>null
        ]);
    }
}
