<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Session;
use App\Entity\State;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'html5' => true,
                'label' => 'Date de session',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('sessionTimeText', TextType::class, [
                'label' => 'Temps de jeu',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'onkeydown' => 'addTimeSeparatorSession()'
                ]
            ])
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('game')
                        ->leftJoin('game.state', 'state')
                        ->select('game', 'state')
                        ->orWhere('game.state = :state')
                        ->setParameter('state', 2)
                        ->orderBy('game.name', 'DESC');
                },
                'label' => 'Jeu'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
