<?php

namespace App\Form\Postgrado;

use App\Entity\Personal\Persona;
use App\Entity\Postgrado\Comision;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Postgrado\Copep;
use App\Entity\Postgrado\RolComision;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Repository\Estructura\ResponsabilidadRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RevisionDictamenType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dictamenFinal', FileType::class, [
                'label' => 'Dictamen general',
                'mapped' => false,
                'required' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitudPrograma::class,
        ]);
    }
}
