<?php

namespace App\Services;

use App\Entity\Cours;
use App\Form\AddStagiaireCoursType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use function PHPSTORM_META\type;

class FormCoursService {
  private ?Cours $cours;

  public function __construct(
    private EntityManagerInterface $em,
    private FormFactoryInterface $formFactory,
    private StagiaireRepository $stagiaireRepo
  )
  {
    
  }
  private function initializeOptionsAdd()
  {
    return ['inscrits' => $this->stagiaireRepo->getStagiairesNotSubscribed($this->cours), 'label_submit' => 'Ajouter'];
  }

  private function initializeOptionsRemove()
  {
    return ['inscrits' => $this->cours->getStagiaire(), 'label_submit' => 'Supprimer'];
  }
  /**
   * @param bool $add si true alors ajout stagiaire sinon remove
   */
  public function createNamedForm(Cours $cours, bool $add = true )
  {
    $this->cours = $cours;
    if ($add) {
      $options = $this->initializeOptionsAdd();
      $name = 'add_stagiaire';
    } else {
      $options = $this->initializeOptionsRemove();
      $name = 'remove_stagiaire';
    }
    return $this->formFactory->createNamed($name, AddStagiaireCoursType::class, $this->cours, $options);
  }

  public function submitForm(FormInterface $form, Cours $cours, ?Request $request):bool
  {
    if ($form->isSubmitted() && $form->isValid()) {
      //if($request->request->has())
      $this->em->persist($cours);
      $this->em->flush();
      return true;
    }
    return false;
  }

  private function addStagiaire(FormInterface $form)
  {
    if ($form->has('stagiaire')){
      $stagiaireForm = $form->get('stagiaires')->getData();
      foreach ($stagiaireForm as $stagiaire) {
        if(!$this->cours->getStagiaires()->contains($stagiaire)) {
          $this->cours->addStagiaire($stagiaire);
        }
      }
    }
  }
}