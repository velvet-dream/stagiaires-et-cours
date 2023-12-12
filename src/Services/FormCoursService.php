<?php

namespace App\Service;

use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class FormCoursService {

  public function __construct(
    private EntityManagerInterface $em
  )
  {
    
  }

  public function submitForm(FormInterface $form, Cours $cours):bool
  {
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($cours);
      $this->em->flush();
      return true;
    }
    return false;
  }
}