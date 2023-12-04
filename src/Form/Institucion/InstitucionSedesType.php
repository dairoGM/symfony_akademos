<?php

namespace App\Form\Institucion;

use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionSedees;
use App\Entity\Institucion\InstitucionSedes;
use App\Entity\Institucion\TipoInstitucion;
use App\Entity\Personal\GradoAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstitucionSedesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreSede', TextType::class, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('coordenadasSede', TextType::class, [
                'label' => 'Coordenadas',
                'required' => false,
//                'constraints' => [
//                    new NotBlank([], 'Este valor no debe estar en blanco.')
//                ]
            ])
            ->add('direccionSede', TextType::class, [
                'label' => 'Dirección',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstitucionSedes::class,
        ]);
    }
}
