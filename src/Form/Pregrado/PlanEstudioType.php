<?php

namespace App\Form\Pregrado;

use App\Entity\Personal\Carrera;
use App\Entity\Postgrado\RamaCiencia;
use App\Entity\Pregrado\CursoAcademico;
use App\Entity\Pregrado\Oace;
use App\Entity\Pregrado\OrganismoDemandante;
use App\Entity\Pregrado\OrganismoFormador;
use App\Entity\Pregrado\PlanEstudio;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
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

class PlanEstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cursoAcademico', EntityType::class, [
                'label' => 'Curso académico inicial',
                'class' => CursoAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('carrera', EntityType::class, [
                'class' => SolicitudProgramaAcademico::class,
                'label' => 'Programa académico de pregrado',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.estadoProgramaAcademico', 'e')
                        ->where('u.activo = true and e.id=2')
                        ->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
//            ->add('tipoProgramaAcademico', EntityType::class, [
//                'label' => 'Tipo de programa académico',
//                'class' => TipoProgramaAcademico::class,
//                'choice_label' => 'nombre',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
//                },
//                'placeholder' => 'Seleccione',
//                'empty_data' => null
//            ])
            ->add('annoAprobacion', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Año de aprobación',
                'attr' => [
                    'maxlength' => 4,
                    'minlength' => 4
                ],
                'constraints' => [new Length(["min" => 4, 'minMessage' => 'El número mínimo de caracteres es {{ limit }}', "max" => 4, 'maxMessage' => 'El número máximo de caracteres es {{ limit }}']), new NotBlank()]
            ])
            ->add('fechaAprobacion', TextType::class, [
                'label' => 'Fecha de aprobación',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
//            ->add('oace', EntityType::class, [
//                'label' => 'OACE',
//                'class' => Oace::class,
//                'choice_label' => 'nombre',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
//                },
//                'placeholder' => 'Seleccione',
//                'empty_data' => null
//            ])
            ->add('organismoFormador', EntityType::class, [
                'label' => 'Organismo formador',
                'class' => OrganismoFormador::class,
                'choice_label' => 'nombre',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
//            ->add('organismoDemandante', EntityType::class, [
//                'label' => 'Organismo o entidad demandante',
//                'class' => OrganismoDemandante::class,
//                'choice_label' => 'nombre',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
//                },
//                'required' => false,
//                'placeholder' => 'Seleccione',
//                'empty_data' => null
//            ])
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
                'required' => ($options['action'] == 'registrar'),
            ])
//            ->add('duracionCursoDiurno', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
//                'label' => 'Duración del Curso Diurno (Años)',
//                'required' => false,
//                'attr' => [
//                    'min' => 1
//                ]
//            ])
//            ->add('duracionCursoPorEncuentro', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
//                'label' => 'Duración del Curso por Encuentros (Años)',
//                'required' => false,
//                'attr' => [
//                    'min' => 1
//                ]
//            ])
//            ->add('duracionCursoDistancia', ChoiceType::class, [
//                'label' => 'Duración del Curso a Distancia',
//                'choices' => ['No' => 'No', 'Sí' => 'Sí'],
//                'attr' => [
//                    'class' => 'form-control'
//                ],
//                'constraints' => [
//                    new NotBlank([], 'Este valor no debe estar en blanco.')
//                ],
//                'required' => false,
//            ])
            ->add('descripcionPlanEstudio', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('documentoEjecutivo', FileType::class, [
                'label' => 'Documento ejecutivo',
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanEstudio::class,
        ]);
    }
}
