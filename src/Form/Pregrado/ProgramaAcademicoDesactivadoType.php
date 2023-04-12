<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Pregrado\CursoAcademico;
use App\Entity\Pregrado\ProgramaAcademicoDesactivado;
use App\Entity\Pregrado\ProgramaAcademicoReabierto;
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

class ProgramaAcademicoDesactivadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('solicitudCentroRector', FileType::class, [
                'label' => 'Solicitud del centro rector',
                'required' => 'registrar' == $options['solicitudCentroRector'],
                'mapped' => false,
            ])
            ->add('dictamenAprobacion', FileType::class, [
                'label' => 'Dictamen de aprobación',
                'required' => 'registrar' == $options['dictamenAprobacion'],
                'mapped' => false,
            ])
            ->add('resolucionDesactivacion', FileType::class, [
                'label' => 'Resolución',
                'required' => 'registrar' == $options['resolucion'],
                'mapped' => false,
            ])
            ->add('cursoAcademico', EntityType::class, [
                'label' => 'Curso académico',
                'class' => CursoAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('fechaEliminacion', TextType::class, [
                'label' => 'Fecha de eliminación',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgramaAcademicoDesactivado::class,
            'resolucion' => null,
            'solicitudCentroRector' => null,
            'dictamenAprobacion' => null,
        ]);
    }
}
