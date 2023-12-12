<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Stagiaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            // ->add('stagiaire', EntityType::class, [
            //     'class' => Stagiaire::class,
            //     'multiple' => true,
            // ])
            // ->add('choix', ChoiceType::class , [
            //     'mapped' => false,
            //     'choices' => [
            //         "encours" => "affichage"
            //     ],
            // ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer le cours',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
