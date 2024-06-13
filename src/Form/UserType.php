<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nombre de usuario',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Usuario',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('isDeveloper', CheckboxType::class, [
                'label' => 'Desarrollador',
                'required' => false,
                'attr' => [
                    'class' => 'form-checkbox'
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('isAdmin', CheckboxType::class, [
                'label' => 'Administrador',
                'required' => false,
                'attr' => [
                    'class' => 'form-checkbox'
                ],
                'label_attr' => ['class' => 'form-label']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
