<?php

namespace App\Form\Admin;

use App\Entity\Security\User;
use App\Entity\Security\Rol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;
use Doctrine\ORM\EntityRepository;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo',
                'constraints' => [
                    new NotBlank([],'Este valor no debe estar en blanco.')
                ]
            ])              
            ->add('userRoles', EntityType::class, [
                'class' => Rol::class,
                'label' => 'Roles',
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')->orderBy('r.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione',
                'empty_data' => null,
                'multiple' => true,
                'required' => false
            ]);

        if ($options['action'] == 'registrar') {
            $builder->add('passwordPlainText', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Contraseña no coincide.',
                'constraints' => [
                    new NotBlank()
                ],
                'required' => true,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Confirmar contraseña'],
            ]);
        }
        else if ($options['action'] == 'modificar') {
            $builder->add('passwordPlainText', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Contraseña no coincide.',
                'options' => ['attr' => ['class' => 'password-field']],                
                'required' => false,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Confirmar contraseña'],
            ]);
        }        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}
