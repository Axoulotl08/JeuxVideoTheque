<?php

namespace App\Form;

use App\Entity\Console;
use App\Entity\Game;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('boxGame', CheckboxType::class,  [
                'label' => 'Version physique',
                'required' => false
            ])
            ->add('collectorVersion', CheckboxType::class,  [
                'label' => 'Version collector',
                'required' => false
            ])
            ->add('saleDate', DateType::class, [
                'html5' => true,
                'label' => 'Date de sortie',
                'widget' => 'single_text'
            ])
            ->add('state', EntityType::class, [
                'class' =>  State::class,
                'choice_label' => 'name',
                'label' => 'State'
            ])
            ->add('console', EntityType::class, [
                'class' =>  Console::class,
                'choice_label' => 'name',
                'label' => 'Console'
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
