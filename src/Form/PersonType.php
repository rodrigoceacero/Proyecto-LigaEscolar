<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nombre',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Nombre',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Apellidos',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Apellidos',
                ],
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'label' => 'Equipo',
                'label_attr' => ['class' => 'form-label'],
                'choice_label' => 'name',
                'required' => true,
                'placeholder' => 'Seleccione un equipo...',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('isPlayer', CheckboxType::class, [
                'label' => 'Jugador',
                'required' => false,
                'attr' => [
                    'class' => 'form-checkbox'
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('isTeacher', CheckboxType::class, [
                'label' => 'Profesor',
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
            'data_class' => Person::class,
        ]);
    }
}
