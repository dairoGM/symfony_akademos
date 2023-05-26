<?php

namespace App\Form\Institucion;

use App\Entity\Estructura\Estructura;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionFacultades;
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

class InstitucionFacultadesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idCategoriaEstructura = $options['idCategoriaEstructura'];
        $this->idEstructura = $options['idEstructura'];
        $builder
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $categoriaEstructura = $this->idCategoriaEstructura;
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.categoriaEstructura = '$categoriaEstructura' ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione'
            ])
//            ->add('nombreFacultad', TextType::class, [
//                'label' => 'Nombre',
//                'constraints' => [
//                    new NotBlank([], 'Este valor no debe estar en blanco.')
//                ]
//            ])
            ->add('descripcionFacultad', TextType::class, [
                'label' => 'Descripción',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstitucionFacultades::class,
        ]);
    }
}
