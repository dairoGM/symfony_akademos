<?php

namespace App\Form\Estructura;

use App\Entity\Estructura\Plaza;
use App\Entity\Estructura\Responsabilidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlazaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cantidad', IntegerType::class, [
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ],
                'data' => 1
            ])
            ->add('responsabilidad', EntityType::class, [
                'class' => Responsabilidad::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plaza::class,
        ]);
    }
}
