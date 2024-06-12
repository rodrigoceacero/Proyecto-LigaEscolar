<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'Temporada',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Años de la temporada (2023/24)',
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Fecha de inicio',
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input-date form-date',
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Fecha de inicio',
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input-date form-date',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
