<?php

namespace App\Form\Pregrado;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\Comision;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Postgrado\RolComision;
use App\Entity\Pregrado\Documento;
use App\Entity\Pregrado\PlanEstudioDocumento;
use App\Repository\Estructura\ResponsabilidadRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentosPlanEstudioType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('documentoFisico', FileType::class, [
                'label' => 'Adjuntar documento',
                'mapped' => false,
                'required' => true,
            ])
            ->add('documento', EntityType::class, [
                'label' => 'Documento',
                'class' => Documento::class,
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
            'data_class' => PlanEstudioDocumento::class,
        ]);
    }
}
