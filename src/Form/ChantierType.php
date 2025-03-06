<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Client;
use App\Entity\Employe;
use App\Repository\EmployeRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du chantier :',
                'attr' => [
                    'placeholder' => 'Nom du chantier',
                    'class' => 'aaaaaa'
                ]
            ])
            ->add('numero_de_la_voie', TextType::class, [
                'label' => 'Numéro de la voie :',
                'attr' => [
                    'placeholder' => 'Numéro de la voie',
                    'class' => 'aaaaaa'
                ]
            ])
            ->add('type_de_voie', ChoiceType::class,
                [
                    'label' => 'Type de voie :',
                    'choices' => [
                        'Rue' => 'Rue',
                        'Avenue' => 'Avenue',
                        'Boulevard' => 'Boulevard',
                        'Impasse' => 'Impasse',
                        'Place' => 'Place',
                        'Route' => 'Route',
                        'Chemin' => 'Chemin',
                        'Allée' => 'Allée',
                        'Passage' => 'Passage',
                        'Quai' => 'Quai',
                        'Square' => 'Square',
                        'Cours' => 'Cours',
                        'Rond-Point' => 'Rond-Point',
                        'Hameau' => 'Hameau',
                        'Lotissement' => 'Lotissement',
                        'Parvis' => 'Parvis',
                        'Promenade' => 'Promenade',
                        'Villa' => 'Villa']
                    ]

            )
            ->add('libelle_de_la_voie', TextType::class, [
                'label' => 'Libellé de la voie :',
                'attr' => [
                    'placeholder' => 'Libellé de la voie',
                    'class' => 'aaaaaa'
                ]
            ])
            ->add('code_postal', NumberType::class, [
                'label' => 'Code postal :',
                'attr' => [
                    'placeholder' => 'Code postal',
                    'class' => 'aaaaaa'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville :',
                'attr' => [
                    'placeholder' => 'Ville',
                    'class' => 'aaaaaa'
                ]
            ])
            ->add('date_de_debut', null, [
                'label' => 'Date de début :',
                'widget' => 'single_text',
            ])
            ->add('date_de_fin', null, [
                'label' => 'Date de fin :',
                'widget' => 'single_text',
            ])
            ->add('chef_de_chantier', EntityType::class, [
                'label' => 'Chef de chantier :',
                'class' => Employe::class,
                'choice_label' => 'nom',
                'query_builder' => function (EmployeRepository $er) {
                    return $er->createQueryBuilder('e')
                              ->where('e.est_chef_de_chantier = :val')
                              ->setParameter('val', true);
                },
                'attr' => [
                    'class' => 'aaaaaa'
                ]
            ])
            ->add('client', EntityType::class, [
                'label' => 'Client :',
                'class' => Client::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chantier::class,
        ]);
    }
}
