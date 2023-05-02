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
use App\Entity\Personal\Profesion;
use App\Entity\Personal\Sexo;
use App\Repository\Estructura\ResponsabilidadRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonaType extends AbstractType
{

    private $idProvincia;
    private $idCategoriaEstructura;
    private $idEstructura;
    private $responsabilidadRepository;

    public function __construct(ResponsabilidadRepository $responsabilidadRepository)
    {
        $this->idProvincia = null;
        $this->idCategoriaEstructura = null;
        $this->idEstructura = null;
        $this->responsabilidadRepository = $responsabilidadRepository;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idProvincia = $options['provincia_choices'];
        $this->idCategoriaEstructura = $options['categoria_estructura_choices'];
        $this->idEstructura = $options['estructura_choices'];

        $builder
            ->add('carnetIdentidad', TextType::class, [
                'label' => 'Carné de identidad',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('numeroSerieCarnetIdentidad', TextType::class, [
                'label' => 'Número de serie del CI',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
//            ->add('twitter', TextType::class, [
//                'required' => false
//            ])
            ->add('primerNombre', TextType::class, [
                'label' => 'Primer nombre',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('segundoNombre', TextType::class, [
                'label' => 'Segundo nombre',
                'required' => false
            ])
            ->add('primerApellido', TextType::class, [
                'label' => 'Primer apellido',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('segundoApellido', TextType::class, [
                'label' => 'Segundo apellido',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('fechaNacimiento', TextType::class, [
                'label' => 'Fecha de nacimiento',
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo',
                'required' => false
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono particular',
                'required' => false,
                "attr" => [
                    "data-inputmask" => '"mask": "(999) 999-9999"',
                    "data-mask" => ''
                ]
            ])
            ->add('celular', TextType::class, [
                'label' => 'Teléfono celular',
                'required' => false,
                "attr" => [
                    "data-inputmask" => '"mask": "99-99-99-99"',
                    "data-mask" => ''
                ]
            ])
            ->add('sexo', EntityType::class, [
                'class' => Sexo::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('provincia', EntityType::class, [
                'class' => Provincia::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('municipio', EntityType::class, [
                'class' => Municipio::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $pro = $this->idProvincia;
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.provincia = '$pro' ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección particular',
                'required' => false
            ])
            ->add('clasificacionPersona', EntityType::class, [
                'class' => ClasificacionPersona::class,
                'label' => 'Clasificación de persona',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])->add('nivelEscolar', EntityType::class, [
                'class' => NivelEscolar::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('categoriaDocente', EntityType::class, [
                'class' => CategoriaDocente::class,
                'label' => 'Categoría docente',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('profesion', EntityType::class, [
                'class' => Profesion::class,
                'label' => 'Profesión',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('gradoAcademico', EntityType::class, [
                'class' => GradoAcademico::class,
                'choice_label' => 'nombre',
                'label' => 'Grado académico',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('categoriaInvestigativa', EntityType::class, [
                'class' => CategoriaInvestigativa::class,
                'label' => 'Categoría investigativa',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('carrera', EntityType::class, [
                'class' => Carrera::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'required' => false
            ])
            ->add('categoriaEstructura', EntityType::class, [
                'class' => CategoriaEstructura::class,
                'label' => 'Categoría de estructura',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {

                    return $er->createQueryBuilder('u')->where("u.activo = true   ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $pro = $this->idCategoriaEstructura;
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.categoriaEstructura = '$pro' ")->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false

            ])
            ->add('responsabilidad', ChoiceType::class, [
                'choices' => $this->responsabilidadRepository->getQueryBuilderForm($this->idEstructura),
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('usuario', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'mapped' => false,
                'attr' => [
                    'readonly' => ($options['accion'] == 'registrar')
                ]
            ])
            ->add('contrasena', PasswordType::class, [
                'label' => 'Contraseña',
                'constraints' => [
                    new NotBlank()
                ],
                'mapped' => false,
                'required' => ($options['accion'] == 'registrar')
            ])
            ->add('contrasena2', PasswordType::class, [
                'label' => 'Confirmar contraseña',
                'constraints' => [
                    new NotBlank()
                ],
                'mapped' => false,
                'required' => ($options['accion'] == 'registrar')
            ])
            ->add('foto', FileType::class, array(
                "attr" => array("type" => "file"),
                "required" => false,
                "mapped" => false,

            ));
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
            'provincia_choices' => [],
            'estructura_choices' => [],
            'categoria_estructura_choices' => [],
            'accion' => [],
        ]);
    }
}
