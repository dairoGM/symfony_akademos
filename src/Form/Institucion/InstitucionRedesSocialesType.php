<?php

namespace App\Form\Institucion;

use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionFacultades;
use App\Entity\Institucion\InstitucionRedes;
use App\Entity\Institucion\InstitucionRedesSociales;
use App\Entity\Institucion\RedSocial;
use App\Entity\Institucion\TipoInstitucion;
use App\Entity\Personal\GradoAcademico;
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
use Symfony\Component\Validator\Constraints\NotBlank;

class InstitucionRedesSocialesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('perfil', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('redSocial', EntityType::class, [
                'class' => RedSocial::class,
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
            'data_class' => InstitucionRedesSociales::class,
        ]);
    }
}
