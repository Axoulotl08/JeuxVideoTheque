<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'html5' => true,
                'label' => 'Date de début',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('finishDate', DateType::class, [
                'html5' => true,
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('trophyPourcentage', IntegerType::class, [
                'label' => 'Trophées',
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 100
                ]
            ])
            ->add('gameTimeText', TextType::class, [
                'label' => 'Temps de jeu',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'onkeydown' => 'addTimeSeparator()'
                ]
            ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'name',
                'label' => 'Statut'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
