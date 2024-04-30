<?php

namespace App\Form\Tramite;

use App\Entity\Estructura\Pais;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\EstadoFichaSalida;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Tramite\FichaSalidaEstado;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Tramite\TipoPasaporte;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CambioEstadoSalidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('estadoFichaSalida', EntityType::class, [
                'class' => EstadoFichaSalida::class,
                'label' => 'Estado',
                'required' => false,
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
            'data_class' => FichaSalidaEstado::class,
        ]);
    }
}
