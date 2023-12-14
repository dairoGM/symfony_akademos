<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Pregrado\OrganismoDemandante;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AprobarModificarSolicitudProgramaAcademicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('organismoDemandante', EntityType::class, [
                'label' => 'Organismo o entidad demandante',
                'class' => OrganismoDemandante::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('organismoFormador', EntityType::class, [
                'label' => 'Organismo formador',
                'class' => Institucion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'required' => false,
                'empty_data' => null
            ])
            ->add('categoriaAcreditacion', EntityType::class, [
                'label' => 'Categoría de acreditación',
                'class' => CategoriaAcreditacion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('duracionCursoDiurno', NumberType::class, [
                'label' => '  ',
                'required' => false,
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('duracionCursoPorEncuentro', NumberType::class, [
                'label' => '  ',
                'required' => false,
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('duracionCursoADistancia', NumberType::class, [
                'label' => '  ',
                'required' => false,
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('descripcionAprobacion', TextareaType::class, [
                'label' => 'Caracterización',
                'required' => false,
            ])
            ->add('modalidadDiurno', CheckboxType::class, [
                'required' => false,
                'label' => 'Diurno'
            ])
            ->add('modalidadPorEncuentro', CheckboxType::class, [
                'required' => false,
                'label' => 'Por encuentros'
            ])
            ->add('modalidadADistancia', CheckboxType::class, [
                'required' => false,
                'label' => 'A distancia'
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
