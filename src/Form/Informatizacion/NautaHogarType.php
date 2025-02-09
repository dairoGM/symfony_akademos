<?php

namespace App\Form\Informatizacion;

use App\Entity\Estructura\Estructura;
use App\Entity\Informatizacion\NautaHogar;
use App\Entity\Informatizacion\PublicoObjetivo;
use App\Entity\Informatizacion\ServicioContratado;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NautaHogarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroTelefono', TextType::class, [
                'label' => 'Número de teléfono'
            ])
            ->add('direccionInstalacion', TextareaType::class, [
                'label' => 'Dirección de instalación del servicio'
            ])
            ->add('servicioContratado', EntityType::class, [
                'label' => 'Servicio contratado',
                'class' => ServicioContratado::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('precio', TextType::class, [

            ])->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'label' => 'Institución',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.esEntidad = true')
                        ->orderBy('e.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NautaHogar::class,
        ]);
    }
}
