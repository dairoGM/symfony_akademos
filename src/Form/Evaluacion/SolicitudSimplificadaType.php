<?php

namespace App\Form\Evaluacion;

use App\Entity\Evaluacion\Convocatoria;
use App\Entity\Evaluacion\Solicitud;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SolicitudSimplificadaType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idEstructuraPersonaAutenticada = $options['idEstructuraPersonaAutenticada'];
        $builder
            ->add('convocatoria', EntityType::class, [
                'label' => 'Convocatoria',
                'class' => Convocatoria::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $currentDate = date("Y-m-d");
                    return $er->createQueryBuilder('u')->where("u.fechaFin >= '$currentDate'")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => true,
            ])
            ->add('cartaSolicitud', FileType::class, [
                'label' => 'Carta de solicitud',
                'mapped' => false,
                'required' => $options['cartaSolicitud'] == 'registrar',
            ])
            ->add('fechaPropuesta', TextType::class, [
                'label' => 'Fecha propuesta',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
            'cartaSolicitud' => null,
            'idEstructuraPersonaAutenticada' => null,
        ]);


    }
}
