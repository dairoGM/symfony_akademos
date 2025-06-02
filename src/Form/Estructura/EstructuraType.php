<?php

namespace App\Form\Estructura;

use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\TipoEstructura;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use Doctrine\DBAL\Types\TimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EstructuraType extends AbstractType
{
    private $idProvincia;
    private $estructuraNegocio;

    public function __construct()
    {
        $this->idProvincia = null;
        $this->estructuraNegocio = [];
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->idProvincia = $options['data_choices'];
        $this->estructuraNegocio = $options['estructuraNegocio'];
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('siglas', TextType::class, [
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('fechaActivacion', TextType::class, [
                'label' => 'Fecha de activación',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ],
                'mapped' => false,
                'attr' => [
                    'class' => 'date-time-picker'
                ],
            ])
            ->add('tipoEstructura', EntityType::class, [
                'class' => TipoEstructura::class,
                'label' => 'Tipo de estructura',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('estructura', EntityType::class, [
                'class' => Estructura::class,
                'label' => 'Subordinado a',
                'choice_label' => function (Estructura $estructura) {
                    $siglasPadre = $estructura->getEstructura() ? $estructura->getEstructura()->getSiglas() : null;
                    return $siglasPadre ? sprintf('(%s) %s', $siglasPadre, $estructura->getNombre()) : $estructura->getNombre();
                },
                'query_builder' => function (EntityRepository $er) {
                    $estructurasNegocio = $this->estructuraNegocio;

                    if (count($estructurasNegocio) > 0) {
                        // Primero obtenemos todas las IDs de las estructuras negocio y sus descendientes
                        $qb = $er->createQueryBuilder('e');
                        $qb->select('e.id')
                            ->where('e.activo = true')
                            ->andWhere($qb->expr()->in('e.id', ':parents'))
                            ->setParameter('parents', array_values($estructurasNegocio));

                        $idsPadres = array_column($qb->getQuery()->getScalarResult(), 'id');

                        // Función recursiva para obtener todas las descendientes
                        $getAllChildrenIds = function ($parentIds) use ($er) {
                            $allChildren = [];
                            $currentLevel = $parentIds;

                            while (!empty($currentLevel)) {
                                $qb = $er->createQueryBuilder('e');
                                $children = $qb->select('e.id')
                                    ->where('e.activo = true')
                                    ->andWhere($qb->expr()->in('e.estructura', ':parents'))
                                    ->setParameter('parents', $currentLevel)
                                    ->getQuery()
                                    ->getScalarResult();

                                $currentLevel = array_column($children, 'id');
                                $allChildren = array_merge($allChildren, $currentLevel);
                            }

                            return $allChildren;
                        };

                        $idsHijos = $getAllChildrenIds($idsPadres);
                        $todasIds = array_unique(array_merge($idsPadres, $idsHijos));

                        // Finalmente buscamos todas las estructuras que están en la lista completa
                        return $er->createQueryBuilder('e')
                            ->where('e.activo = true')
                            ->andWhere('e.id IN(:allIds)')
                            ->setParameter('allIds', $todasIds)
                            ->orderBy('e.nombre', 'ASC');
                    }

                    return $er->createQueryBuilder('e')
                        ->where('e.activo = true')
                        ->orderBy('e.nombre', 'ASC');
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
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
            ])
            ->add('activo', CheckboxType::class, [
                'required' => false,
                'label' => 'Habilitado',
            ])
//            ->add('esEntidad', CheckboxType::class, [
//                'required' => false,
//                'label' => 'Entidad',
//            ])
//            ->add('centroAutorizadoPosgrado', CheckboxType::class, [
//                'required' => false,
//                'label' => 'Centro autorizado de posgrado',
//            ])
            ->add('ubicacion', TextType::class, [
                'label' => 'Ubicación',
                'required' => false
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'required' => false,
                "attr" => [
                    "data-inputmask" => '"mask": "(99) 9 999-99"',
                    "data-mask" => ''
                ]
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'constraints' => [
                    new NotBlank([], 'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('provincia', EntityType::class, [
                'class' => Provincia::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.codigo', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null
            ])
            ->add('municipio', EntityType::class, [
                'class' => Municipio::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    $pro = $this->idProvincia;
                    return $er->createQueryBuilder('u')->where("u.activo = true and u.provincia = '$pro' ")->orderBy('u.codigo', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estructura::class,
            'data_choices' => [],
            'estructuraNegocio' => [],
            'action' => []
        ]);
    }
}
