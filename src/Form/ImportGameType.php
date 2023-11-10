<?php

namespace App\Form;

use App\Service\ImportGameListCSV;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImportGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileName', FileType::class, [
                'label' => 'Fichier CSV',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImportGameListCSV::class,
            'method' => 'POST',
            'csrf_protection' => true
        ]);
    }
}
