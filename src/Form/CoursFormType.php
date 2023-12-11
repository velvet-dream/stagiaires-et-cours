<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Stagiaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('stagiaire', EntityType::class, [
                'class' => Stagiaire::class,
                'choice_label' => function (?Stagiaire $stagiaire): string {
                    return $stagiaire ? ($stagiaire->getId() . "-" . $stagiaire->getNom() . " " . $stagiaire->getPrenom()) : '';
                },
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
