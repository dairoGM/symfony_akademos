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

class SolicitudType extends AbstractType
{
    private $idEstructuraPersonaAutenticada;

    public function __construct()
    {
        $this->idEstructuraPersonaAutenticada = null;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idEstructuraPersonaAutenticada = $options['idEstructuraPersonaAutenticada'];
        $builder
            ->add('tipoSolicitud', ChoiceType::class, [
                'label' => 'Tipo de solicitud',
                'choices' => [
                    'Institución' => 'institucion',
                    'Programa de pregrado' => 'programa_pregrado',
                    'Programa de posgrado' => 'programa_posgrado',
                ],
                'placeholder' => 'Seleccione',
                'required' => true,
            ])
            ->add('convocatoria', EntityType::class, [
                'label' => 'Convocatoria',
                'class' => Convocatoria::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $currentDate = date("Y-m-d");
                    return $er->createQueryBuilder('u')->where("u.creado >= '$currentDate'")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => true,
            ])
            ->add('institucion', TextType::class, [
                'label' => 'Institución',
                'required' => false,
                'mapped' => false,
                "attr" => [
                    'readonly' => true
                ]
            ])
            ->add('programaPregrado', EntityType::class, [
                'label' => 'Programas de pregrado',
                'class' => SolicitudProgramaAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where("u.id = -1")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('programaPosgrado', EntityType::class, [
                'label' => 'Programas de posgrado',
                'class' => SolicitudPrograma::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.id = -1')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('categoriaAcreditacionActual', TextType::class, [
                'label' => 'Categoría de acreditación',
                'required' => true,
                "attr" => [
                    'readonly' => true
                ]
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
