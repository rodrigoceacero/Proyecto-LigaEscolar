<?php

namespace App\Form;

use App\Entity\GameMatch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class GameMatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schedule', DateTimeType::class, [
                'label' => 'Fecha del partido',
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input-date form-date',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La fecha es obligatoria',
                    ]),
                    new NotNull([
                        'message' => 'La fecha es obligatoria',
                    ]),
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Localización',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Localización',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La localización es obligatoria',
                    ]),
                    new NotNull([
                        'message' => 'La localización es obligatoria',
                    ]),
                ],
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Finalizado',
                'required' => false,
                'attr' => [
                    'class' => 'form-checkbox'
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('details', TextType::class, [
                'label' => 'Detalles',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Detalles',
                ],
            ])
            ->add('teams', CollectionType::class, [
                'entry_type' => TeamMatchGameType::class,
                'allow_add' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameMatch::class,
        ]);
    }
}
