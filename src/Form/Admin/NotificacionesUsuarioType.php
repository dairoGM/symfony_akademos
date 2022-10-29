<?php

namespace App\Form\Admin;

use App\Entity\Admin\CategoriaNotificacion;
use App\Entity\Admin\Notificacion;
use App\Entity\NotificacionesUsuario;
use App\Entity\Security\User;
use App\Repository\Admin\CategoriaNotificacionRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NotificacionesUsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'required' => false
            ])
            ->add('texto', TextareaType::class, [

                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])
            ->add('usuarioRecive', EntityType::class, [
                'class' => User::class,
                'label' => 'Lista de usuarios',
                'choice_label' => 'email',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.activo = true')->orderBy('u.email', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'multiple' => true
            ]) ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NotificacionesUsuario::class,
        ]);
    }
}
