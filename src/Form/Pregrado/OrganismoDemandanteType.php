<?php

namespace App\Form\Pregrado;

use App\Entity\Postgrado\RolComision;
use App\Entity\Pregrado\TipoOrganismo;
use App\Entity\Pregrado\TipoPrograma;
use App\Entity\Pregrado\OrganismoDemandante;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganismoDemandanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])->add('siglas', TextType::class, [
                'constraints' => [new Length(["min" => 3, 'minMessage' => 'El número mínimo de caracteres es {{ limit }}', "max" => 5, 'maxMessage' => 'El número máximo de caracteres es {{ limit }}']), new NotBlank()]
            ])
//            ->add('tipoOrganismo', EntityType::class, [
//                'label' => 'Tipo de organismo',
//                'class' => TipoOrganismo::class,
//                'choice_label' => 'nombre',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
//                },
//                'placeholder' => 'Seleccione',
//                'empty_data' => null
//            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrganismoDemandante::class,
        ]);
    }
}
