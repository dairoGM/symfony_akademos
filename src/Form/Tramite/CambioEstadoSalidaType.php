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
    private $estadoActual;
    private $estadoFinal;

    public function __construct()
    {
        $this->estadoActual = null;
        $this->estadoFinal = null;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->estadoActual = $options['estadoActual'];
        $this->estadoFinal = $options['estadoFinal'];
        $builder
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('estadoFichaSalida', EntityType::class, [
                'class' => EstadoFichaSalida::class,
                'label' => 'Estado',
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {

                    $estadoFinal = implode(",", $this->estadoFinal);
                    return $er->createQueryBuilder('u')->where("u.id IN ($estadoFinal)");
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichaSalidaEstado::class,
            'estadoActual' => [],
            'estadoFinal' => [],
        ]);
    }
}
