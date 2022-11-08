<?php

namespace App\Form\Postgrado;

use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Postgrado\CategoriaCategorizacion;
use App\Entity\Postgrado\EstadoPrograma;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NoAprobarProgramaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('dictamenFinal', FileType::class, [
                'label' => 'Dictamen',
                'mapped' => false,
                'required' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudPrograma::class,
        ]);
    }
}
