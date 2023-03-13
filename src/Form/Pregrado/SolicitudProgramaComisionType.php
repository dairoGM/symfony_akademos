<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Pregrado\ComisionNacional;
use App\Entity\Pregrado\SolicitudProgramaAcademicoComisionNacional;
use App\Entity\Pregrado\SolicitudProgramaAcademicoInstitucion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudProgramaComisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comisionNacional', EntityType::class, [
                'label' => 'Comisión',
                'class' => ComisionNacional::class,
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
            'data_class' => SolicitudProgramaAcademicoComisionNacional::class,
        ]);
    }
}
