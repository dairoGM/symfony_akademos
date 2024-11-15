<?php

namespace App\Form\Tramite;

use App\Entity\Economia\ConceptoGasto;
use App\Entity\Estructura\Pais;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Tramite\Pasaporte;
use App\Entity\Tramite\TipoPasaporte;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasaporteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipoPasaporte', EntityType::class, [
                'class' => TipoPasaporte::class,
                'label' => 'Tipo de pasaporte',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('fechaEmisionPasaporte', TextType::class, [
                'label' => 'Fecha de emisión',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('fechaCaducidadPasaporte', TextType::class, [
                'label' => 'Fecha de caducidad',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('numeroPasaporte', TextType::class, [
                'label' => 'Número de pasaporte',
                'required' => true,
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
            ])
            ->add('cara1', FileType::class, array(
                "attr" => array("type" => "file"),
                "required" => false,
                "mapped" => false,

            ))
            ->add('cara2', FileType::class, array(
                "attr" => array("type" => "file"),
                "required" => false,
                "mapped" => false,

            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pasaporte::class,
        ]);
    }
}
