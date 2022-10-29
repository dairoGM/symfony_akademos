<?php

namespace App\Form\Postgrado;

use App\Entity\Postgrado\CategoriaCategorizacion;
use App\Entity\Postgrado\EstadoPrograma;
use App\Entity\Postgrado\NivelAcreditacion;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CambioEstadoProgramaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annoAcreditacion', TextType::class, [
                'label' => 'Año de acreditación',
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('codigoPrograma', TextType::class, [
                'label' => 'Código',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Dictamen / Descripción',
                'required' => false,
            ])
            ->add('resolucionPrograma', FileType::class, [
                'label' => 'Resolución del programa',
                'mapped' => false,
                'required' => true,
            ])
            ->add('estadoPrograma', EntityType::class, [
                'label' => 'Estado',
                'class' => EstadoPrograma::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('nivelAcreditacion', EntityType::class, [
                'label' => 'Nivel de acreditación',
                'class' => NivelAcreditacion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('categoriaCategorizacion', EntityType::class, [
                'label' => 'Categoría de categorización',
                'class' => CategoriaCategorizacion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('fechaProximaAcreditacion', TextType::class, [
                'label' => 'Fecha de próxima de acreditación',
                'mapped' => false,
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudPrograma::class,
        ]);
    }
}
