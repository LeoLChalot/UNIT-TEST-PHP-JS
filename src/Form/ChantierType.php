<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Client;
use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom du chantier',
            ])
            ->add('date_de_debut', null, [
                'widget' => 'single_text',
            ])
            ->add('date_de_fin', null, [
                'widget' => 'single_text',
            ])
            ->add('chef_de_chantier', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom',
                'query_builder' => function (EmployeRepository $er) {
                    return $er->createQueryBuilder('e')
                              ->where('e.est_chef_de_chantier = :val')
                              ->setParameter('val', true);
                },
            ])
            ->add('client', EntityType::class, [
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
