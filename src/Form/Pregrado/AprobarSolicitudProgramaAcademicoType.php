<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AprobarSolicitudProgramaAcademicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cartaAprobacion', FileType::class, [
                'label' => 'Carta de aprobación',
                'required' => $options['cartaAprobacion'] == 'registrar',
                'mapped' => false,
            ])
            ->add('fechaAprobacion', TextType::class, [
                'label' => 'Fecha de aprobación',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('centroRector', EntityType::class, [
                'label' => 'Centro rector',
                'class' => Institucion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('duracionCursoDiurno', IntegerType::class, [
                'label' => 'Duración del curso diurno (Años)',
                'required' => false,
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('duracionCursoPorEncuentro', IntegerType::class, [
                'label' => 'Duración del curso por encuentros (Años)',
                'required' => false,
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('descripcionAprobacion', TextareaType::class, [
                'label' => 'Caracterización',
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudProgramaAcademico::class,
            'cartaAprobacion' => null
        ]);
    }
}
