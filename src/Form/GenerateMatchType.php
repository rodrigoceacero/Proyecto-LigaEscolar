<?php

namespace App\Form;

use App\Entity\Sport;
use App\Entity\Season;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GenerateMatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'name',
                'label' => 'Deporte',
                'placeholder' => 'Selecciona un deporte',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('s')
                        ->where('s.active = 1')
                        ->orderBy('s.name');
                },
                'attr' => ['class' => 'form-select-deporte select2'],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'El deporte es obligatorio']),
                ],
            ])
            ->add('season', EntityType::class, [
                'class' => Season::class,
                'choice_label' => 'description',
                'label' => 'Temporada',
                'placeholder' => 'Selecciona una temporada',
                'attr' => [
                    'class' => 'form-select-deporte select2',
                ],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank(['message' => 'La temporada es obligatoria']),
                ],
            ])
            ->add('generate', SubmitType::class, [
                'label' => 'Generar',
                'attr' => ['class' => 'btn-guardar-form'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }

}