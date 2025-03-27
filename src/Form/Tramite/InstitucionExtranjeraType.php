<?php

namespace App\Form\Tramite;

use App\Entity\Estructura\Pais;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Personal\ClasificacionPersona;
use App\Entity\Tramite\ConceptoSalida;
use App\Entity\Tramite\InstitucionExtranjera;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstitucionExtranjeraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('correo', EmailType::class, [
                'label' => 'Correo',
                'required' => false
            ])
            ->add('provincia', TextType::class, [
                'required' => false
            ])
            ->add('sitioWeb', TextType::class, [
                'label' => 'Sitio web',
                'required' => false
            ])
            ->add('siglas', TextType::class, [
                'label' => 'Siglas',
                'required' => false
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'required' => false,
                'attr' => [
                    'data-inputmask' => '"mask": "(999) 999 999 999 999"', // Permite hasta 15 dígitos
                    'data-mask' => ''
                ]
            ])
            ->add('pais', EntityType::class, [
                'class' => Pais::class,
                'label' => 'País',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstitucionExtranjera::class,
        ]);
    }


}
