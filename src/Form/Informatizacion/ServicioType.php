<?php

namespace App\Form\Informatizacion;

use App\Entity\Convenio\Tipo;

;

use App\Entity\Informatizacion\EnlaceConectividad;
use App\Entity\Informatizacion\Marca;
use App\Entity\Informatizacion\PublicoObjetivo;
use App\Entity\Informatizacion\Servicio;
use App\Entity\Informatizacion\TipoConectividad;
use App\Entity\Informatizacion\Visibilidad;
use App\Entity\Institucion\CategoriaAcreditacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clasificacion', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => ['Servicio' => 'Servicio', 'Trámite' => 'Trámite'],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ],
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del servicio',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('direccionWeb', UrlType::class, [
                'label' => 'Dirección web',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('publicoObjetivo', EntityType::class, [
                'label' => 'Público objetivo',
                'class' => PublicoObjetivo::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('visibilidad', EntityType::class, [
                'label' => 'Visibilidad',
                'class' => Visibilidad::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('integracionPasarelaPago', CheckboxType::class, [
                'required' => false,
                'label' => 'Integración con pasarelas de pago'
            ])
            ->add('integracionFirmaDigital', CheckboxType::class, [
                'required' => false,
                'label' => 'Integración con firma digital'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Servicio::class,
        ]);
    }
}
