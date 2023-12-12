<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Stagiaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddStagiaireCoursType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    // check https://symfony.com/doc/current/form/form_customization.html#form-rendering-functions
    $builder
      ->add('stagiaire', EntityType::class, [
          'required' => false,
          'class' => Stagiaire::class,
          'choices' => $options['inscrits'],
          'multiple' => true,
          'expanded' => true,
          'attr' => [
            'class' => 'form-switch'
          ],
      ])
      ->add('submit', SubmitType::class, [
        'label' => $options['label_submit'],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Cours::class,
      'inscrits' => [],
      'label_submit' => 'Sauvegarder',
    ]);
  }
}
