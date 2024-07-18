<?php

namespace App\Form\Evaluacion;

use App\Entity\Evaluacion\CategoriaAcreditacionIES;
use App\Entity\Evaluacion\CategoriaAcreditacionPosgrado;
use App\Entity\Evaluacion\CategoriaAcreditacionPregrado;
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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriaAcreditacionPosgradoType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categoriaAcreditacion', EntityType::class, [
                'label' => 'Categoría de acreditación',
                'class' => CategoriaAcreditacion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('fechaEmision', TextType::class, [
                'label' => 'Fecha de emisión',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ]
            ])
            ->add('numeroPleno', TextType::class, [
                'label' => 'Número del pleno',
                'required' => false,
            ])
            ->add('numeroAcuerdoPleno', TextType::class, [
                'label' => 'Número de acuerdo del pleno',
                'required' => false,
            ])
            ->add('annosVigenciaCategoriaAcreditacion', IntegerType::class, [
                'label' => 'Años de vigencia de la categoría de acreditación',
                'attr' => [
                    'min' => 1
                ],
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoriaAcreditacionPosgrado::class,
        ]);


    }
}
