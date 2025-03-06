<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Employe;
use App\Entity\Tache;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Suspendue' => 'Suspendue',
                    'Annulée' => 'Annulée',
                    'Terminée' => 'Terminée',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('duree', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.25'],
                'label'=> 'Durée en jours',
                'scale' => 2,
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])
            ->add('chantier', EntityType::class, [
                'class' => Chantier::class,
                'choice_label' => 'nom',
            ])
            ->add('employes', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'label' => 'Employés',
                'attr' => [
                    'class' => 'selectMult'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
