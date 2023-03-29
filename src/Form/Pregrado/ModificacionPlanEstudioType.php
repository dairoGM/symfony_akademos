<?php

namespace App\Form\Pregrado;

use App\Entity\Personal\Carrera;
use App\Entity\Postgrado\RamaCiencia;
use App\Entity\Pregrado\CursoAcademico;
use App\Entity\Pregrado\ModificacionPlanEstudio;
use App\Entity\Pregrado\Oace;
use App\Entity\Pregrado\OrganismoDemandante;
use App\Entity\Pregrado\PlanEstudio;
use App\Entity\Pregrado\TipoPrograma;
use App\Entity\Pregrado\TipoProgramaAcademico;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModificacionPlanEstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaAprobacion', TextType::class, [
                'label' => 'Fecha de aprobación',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('planEstudioDoc', FileType::class, [
                'label' => 'Plan de estudio',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('dictamen', FileType::class, [
                'label' => 'Dictamen de modificacón PDE',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('duracionCursoDiurno', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Duración del Curso Diurno (Años)',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('duracionCursoPorEncuentro', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Duración del Curso por Encuentro (Años)',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('duracionCursoDistancia', ChoiceType::class, [
                'label' => 'Duración del Curso a Distancia',
                'choices' => [ 'No' => 'No', 'Sí' => 'Sí'],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
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
            'data_class' => ModificacionPlanEstudio::class,
        ]);
    }
}
