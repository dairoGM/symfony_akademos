<?php

namespace App\Form\Pregrado;

use App\Entity\Institucion\Institucion;
use App\Entity\Postgrado\RolComision;
use App\Entity\Pregrado\OrganismoDemandante;
use App\Entity\Pregrado\TipoOrganismo;
use App\Entity\Pregrado\TipoPrograma;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Pregrado\TipoProgramaAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SolicitudProgramaAcademicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipoProgramaAcademico', EntityType::class, [
                'label' => 'Tipo de programa académico',
                'class' => TipoProgramaAcademico::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
//            ->add('tipoOrganismo', EntityType::class, [
//                'label' => 'Tipo de organismo',
//                'class' => TipoOrganismo::class,
//                'choice_label' => 'nombre',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
//                },
//                'placeholder' => 'Seleccione',
//                'empty_data' => null
//            ])
            ->add('organismoDemandante', EntityType::class, [
                'label' => 'Organismo o entidad demandante',
                'class' => OrganismoDemandante::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('resolucion', FileType::class, [
                'label' => 'Resolución',
                'mapped' => false,
                'required' => false,
            ])
            ->add('fundamentacion', FileType::class, [
                'label' => 'Fundamentación',
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ])
            ->add('nombreSolicitante', TextType::class, [
                'label' => 'Nombre(s) y apellidos',
                'required' => false
            ])
            ->add('correoSolicitante', EmailType::class, [
                'label' => 'Correo',
                'required' => false
            ])
            ->add('telefonoSolicitante', TextType::class, [
                'label' => 'Teléfono',
                'required' => false,
                "attr" => [
                    "data-inputmask" => '"mask": "(99) 9 999-99"',
                    "data-mask" => ''
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudProgramaAcademico::class,
        ]);
    }
}
