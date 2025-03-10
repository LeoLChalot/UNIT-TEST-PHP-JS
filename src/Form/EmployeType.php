<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Metier;
use App\Entity\Tache;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, ['label' => 'Nom :'])
            ->add('prenom' , null, ['label' => 'Prénom :'])
            ->add('telephone' , null, ['label' => 'Téléphone :'])
            ->add('est_chef_de_chantier' , null, ['label' => 'Chef de chantier ?'])
            ->add('metier', EntityType::class, [
                'label' => 'Métier :',
                'class' => Metier::class,
                'choice_label' => 'label',
            ])
            ->add('disponible', ChoiceType::class, [
                'label' => 'Disponible :',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
