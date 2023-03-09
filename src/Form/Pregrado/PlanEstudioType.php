<?php

namespace App\Form\Pregrado;

use App\Entity\Personal\Carrera;
use App\Entity\Postgrado\RamaCiencia;
use App\Entity\Pregrado\CursoAcademico;
use App\Entity\Pregrado\Oace;
use App\Entity\Pregrado\OrganismoDemandante;
use App\Entity\Pregrado\PlanEstudio;
use App\Entity\Pregrado\TipoPrograma;
use App\Entity\Pregrado\TipoProgramaAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlanEstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cursoAcademico', EntityType::class, [
                'label' => 'Curso académico',
                'class' => CursoAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('carrera', EntityType::class, [
                'class' => Carrera::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoProgramaAcademico', EntityType::class, [
                'label' => 'Tipo de programa académico',
                'class' => TipoProgramaAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('annoAprobacion', TextType::class, [
                'label' => 'Año de aprobación',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('fechaAprobacion', TextType::class, [
                'label' => 'Fecha de aprobación',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('oace', EntityType::class, [
                'label' => 'OACE',
                'class' => Oace::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('organismoDemandante', EntityType::class, [
                'label' => 'Organismo demandante',
                'class' => OrganismoDemandante::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'required' => false,
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('ramaCiencia', EntityType::class, [
                'label' => 'Rama de la ciencia',
                'class' => RamaCiencia::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'required' => false,
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('planEstudio', FileType::class, [
                'label' => 'Plan de estudio',
                'mapped' => false,
                'required' => true,
            ])
            ->add('duracionCursoDiurno', TextType::class, [
                'label' => 'Duración del Curso Diurno',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('duracionCursoPorEncuentro', TextType::class, [
                'label' => 'Duración del Curso por Encuentro',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('duracionCursoDistancia', TextType::class, [
                'label' => 'Duración del Curso a Distancia',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('descripcionPlanEstudio', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanEstudio::class,
        ]);
    }
}
