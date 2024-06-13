<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Nombre',
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('school', TextType::class, [
                'label' => 'Escuela',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Escuela',
                    'class' => 'form-input'
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'label' => 'Deporte',
                'required' => true,
                'placeholder' => 'Selecciona un deporte',
                'choice_label' => 'name',
                'multiple' => false,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('s')
                        ->orderBy('s.name');
                },
                'attr' => ['class' => 'form-select-deporte'],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('seasons', EntityType::class, [
                'class' => Season::class,
                'label' => 'Temporadas',
                'required' => false,
                'choice_label' => 'description',
                'multiple' => true,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('s')
                        ->orderBy('s.description');
                },
                'attr' => [
                    'class' => 'form-select-temporadas',
                ],
                'label_attr' => ['class' => 'form-label']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
