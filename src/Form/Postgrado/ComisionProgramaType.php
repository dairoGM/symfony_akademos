<?php

namespace App\Form\Postgrado;

use App\Entity\Postgrado\CategoriaCategorizacion;
use App\Entity\Postgrado\Comision;
use App\Entity\Postgrado\EstadoPrograma;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\Postgrado\SolicitudProgramaComision;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ComisionProgramaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comision', EntityType::class, [
                'label' => 'Comisión',
                'class' => Comision::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'multiple' => true,
            ]);

    }


}
