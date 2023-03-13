<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Pregrado\PlanEstudio;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use App\Entity\Pregrado\SolicitudProgramaAcademicoPlanEstudio;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudProgramaPlanEstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('planEstudio', EntityType::class, [
                'label' => 'Plan de estudio',
                'class' => PlanEstudio::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where("u.activo = true")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudProgramaAcademicoPlanEstudio::class,
        ]);
    }
}
