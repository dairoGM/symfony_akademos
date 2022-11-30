<?php

namespace App\Form\Postgrado;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\Comision;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Postgrado\RolComision;
use App\Repository\Estructura\ResponsabilidadRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudProgramaDictamenType extends AbstractType
{
    private $arrayIdsComision;

    public function __construct()
    {
        $this->arrayIdsComision = null;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->arrayIdsComision = $options['arrayIdsComision'];
        $builder
            ->add('comision', EntityType::class, [
                'label' => 'Comisión',
                'class' => Comision::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.id IN(:valuesItems)")->setParameter('valuesItems', array_values($this->arrayIdsComision))->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('dictamen', FileType::class, [
                'label' => 'Dictamen',
                'mapped' => false,
                'required' => true,
            ])
            ->add('rolComision', EntityType::class, [
                'label' => 'Rol en comisión',
                'class' => RolComision::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'arrayIdsComision' => []
        ]);
    }
}
