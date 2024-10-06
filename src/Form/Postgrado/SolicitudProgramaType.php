<?php

namespace App\Form\Postgrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\ModalidadPrograma;
use App\Entity\Postgrado\PresencialidadPrograma;
use App\Entity\Postgrado\RamaCiencia;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Postgrado\TipoPrograma;
use App\Entity\Postgrado\TipoSolicitud;
use App\Entity\Postgrado\TipoSolicitudClasificacion;
use App\Entity\Postgrado\Universidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SolicitudProgramaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('nombreCoordinador', TextType::class, [
                'label' => 'Nombre y apellidos',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('correoCoordinador', EmailType::class, [
                'label' => 'Correo',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('telefonoCoordinador', TextType::class, [
                'label' => 'Teléfono',
                "attr" => [
                    "data-inputmask" => '"mask": "(99) 9 999-99"',
                    "data-mask" => ''
                ],
                'required' => false
            ])
            ->add('docPrograma', FileType::class, [
                'label' => 'Adjuntar programa',
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ])
            ->add('universidad', EntityType::class, [
                'class' => Institucion::class,
                'choice_label' => 'nombre',
                'label' => 'Institución solicitante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->join('u.estructura', 'e')->where('u.activo = true and e.esEntidad = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('universidadesRed', EntityType::class, [
                'class' => Institucion::class,
                'label' => 'Instituciones que intervienen',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false,
                'multiple' => true,
                'required' => false
            ])
            ->add('originalDe', EntityType::class, [
                'label' => 'Programa original de',
                'class' => Institucion::class,
                'required' => false,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('nombreExistente', EntityType::class, [
                'label' => 'Programas',
                'class' => SolicitudPrograma::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.estadoPrograma=7 and u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoPrograma', EntityType::class, [
                'label' => 'Tipo de programa',
                'class' => TipoPrograma::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoSolicitud', EntityType::class, [
                'label' => 'Tipo de solicitud',
                'class' => TipoSolicitud::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('tipoSolicitudClasificacion', EntityType::class, [
                'label' => 'Clasificación',
                'class' => TipoSolicitudClasificacion::class,
                'choice_label' => 'clasificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.id > 0')->orderBy('u.clasificacion', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => 2,
                'required' => false

            ])
            ->add('ramaCiencia', EntityType::class, [
                'label' => 'Rama de la ciencia',
                'class' => RamaCiencia::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('modalidadPrograma', EntityType::class, [
                'class' => ModalidadPrograma::class,
                'label' => 'Modalidad de dedicación',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('presencialidadPrograma', EntityType::class, [
                'class' => PresencialidadPrograma::class,
                'label' => 'Modalidad de estudio',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'multiple' => true,
                'mapped' => false
            ])
            ->add('duracionPrograma', IntegerType::class, [
                'label' => 'Duración del programa (meses)',
                'required' => false,
                'data' => 1,  // Valor inicial predeterminado
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('cantidadCreditos', IntegerType::class, [
                'label' => 'Cantidad de créditos',
                'required' => false,
                'data' => 1,  // Valor inicial predeterminado
                'attr' => [
                    'min' => 1
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
