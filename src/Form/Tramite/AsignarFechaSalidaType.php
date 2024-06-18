<?php

namespace App\Form\Tramite;

use App\Entity\Estructura\Pais;
use App\Entity\Institucion\Institucion;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\DocumentoSalida;
use App\Entity\Tramite\DocumentoSalidaTramite;
use App\Entity\Tramite\EstadoFichaSalida;
use App\Entity\Tramite\FichaSalida;
use App\Entity\Tramite\FichaSalidaEstado;
use App\Entity\Tramite\InstitucionExtranjera;
use App\Entity\Tramite\TipoPasaporte;
use App\Entity\Tramite\Tramite;
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

class AsignarFechaSalidaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaSalidaReal', TextType::class, [
                'label' => 'Fecha de salida',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('fechaRegresoReal', TextType::class, [
                'label' => 'Fecha de regreso',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentoSalida::class,
        ]);
    }
}
