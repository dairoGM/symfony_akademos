<?php

namespace App\Form\Personal;

use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\Responsabilidad;
use App\Entity\Personal\Carrera;
use App\Entity\Personal\CategoriaDocente;
use App\Entity\Personal\CategoriaInvestigativa;
use App\Entity\Personal\ClasificacionPersona;
use App\Entity\Personal\GradoAcademico;
use App\Entity\Personal\NivelEscolar;
use App\Entity\Personal\Persona;
use App\Entity\Personal\Plantilla;
use App\Entity\Personal\Profesion;
use App\Entity\Personal\Sexo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlantillaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categoriaEstructura', EntityType::class, [
                'class' => CategoriaEstructura::class,
                'choice_label' => 'nombre',
                'label' => 'Categoría de estructura',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where("u.activo = true   ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false,
            ])
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where("u.activo = true ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null

            ])
            ->add('responsabilidad', EntityType::class, [
                'class' => Responsabilidad::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where("u.activo = true   ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plantilla::class
        ]);
    }
}
