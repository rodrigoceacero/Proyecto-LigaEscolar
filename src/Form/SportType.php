<?php

namespace App\Form;

use App\Entity\Sport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',  TextType::class, [
                'label' => 'Nombre',
                'required' => true,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Nombre',
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duracion',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Duración de un partido del deporte (en minutos)',
                    'class' => 'form-input'
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Activo',
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
            'data_class' => Sport::class,
        ]);
    }
}
