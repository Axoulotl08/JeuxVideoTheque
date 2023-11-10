<?php

namespace App\Form;

use App\Data\SearchDataSessions;
use App\Entity\Console;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSessionTypes extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('console', EntityType::class, [
                'label' => 'Console',
                'required' => false,
                'class' => Console::class,
                'choice_label' => 'name'
            ])
            ->add('game', EntityType::class, [
                'label' => 'Jeu',
                'required' => false,
                'class' => Game::class,
                'choice_label' => 'name'
            ])
            ->add('$sessionDateBetweenStart', DateType::class, [
                'label' => 'Date de début',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('$sessionDateBetweenStart',DateType::class, [
                'label' => 'Date de fin',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('sessionDateBetweenStartText', TextType::class, [
                'label' => 'Temps de jeu mini',
                'required' => false,
                'mapped' => false
            ])
            ->add('sessionDateBetweenEndText', TextType::class, [
                'label' => 'Temps de jeu maxi',
                'required' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchDataSessions::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
