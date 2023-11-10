<?php

namespace App\Form;

use App\Data\SearchDataGames;
use App\Entity\Console;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchGamesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titlesearch', TextType::class, [
                'label' => 'Titre recherché',
                'required' => false,
                'attr' => [
                    'placeholder' => '🔍 Rechercher'
                ]
            ])
            ->add('console', EntityType::class, [
                'label' => 'Console',
                'class' => Console::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('startSaleDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('endSaleDate',DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Etat'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchDataGames::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
