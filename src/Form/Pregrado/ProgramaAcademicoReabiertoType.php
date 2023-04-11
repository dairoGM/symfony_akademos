<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Pregrado\CursoAcademico;
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

class ProgramaAcademicoReabiertoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fundamentacionReapertura', FileType::class, [
                'label' => 'Fundamentación',
                'required' => 'registrar' == $options['fundamentacionReapertura'],
                'mapped' => false,
            ])
            ->add('dictamenDgp', FileType::class, [
                'label' => 'Dictamen Dgp',
                'required' => 'registrar' == $options['dictamenDgp'],
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
            ->add('universidades', EntityType::class, [
                'label' => 'Universidades',
                'class' => Institucion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'multiple' => true,
                'empty_data' => null,
                'mapped' => false,
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgramaAcademicoReabierto::class,
            'fundamentacionReapertura' => null,
            'dictamenDgp' => null,
        ]);
    }
}
