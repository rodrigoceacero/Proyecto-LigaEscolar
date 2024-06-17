<?php 

namespace App\Form;

use App\Entity\TeamMatchGame;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class TeamMatchGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('points', ChoiceType::class, [
            'label' => 'Puntuación',
            'choices'  => [
                '0' => 0,
                '1' => 1,
                '3' => 3,
            ],
            'label_attr' => ['class' => 'form-label'],
            'attr' => [
                'class' => 'form-input',
                'placeholder' => 'Detalles',
            ],
            'disabled' => true
        ])
        ->add('score', TextType::class, [
            'label' => 'Puntos / Goles',
            'required' => true,
            'label_attr' => ['class' => 'form-label'],
            'attr' => [
                'placeholder' => 'Puntuación obtenida en el deporte',
                'class' => 'form-input'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'La puntuación no puede estar en blanco',
                ]),
                new NotNull([
                    'message' => 'La puntuación no puede ser nula',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TeamMatchGame::class,
            'empty_data' => function () {
                return new TeamMatchGame();
            },
        ]);
    }
}
