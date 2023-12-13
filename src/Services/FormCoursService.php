<?php
namespace App\Services;

use App\Entity\Cours;
use App\Form\AddStagiaireCoursType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
        return ['inscrits' => $this->stagiaireRepo->getStagiairesNotInscrit($this->cours), 'label_submit' => "Ajouter"];
    }

    private function initializeRemove()
    {
        return ['inscrits' => $this->cours->getStagiaires(), 'label_submit' => "Mettre à jour"];
    }
    /**
     * @param bool $add si true alors ajout stagiaire sinon remove_stagiaire
     */
    public function createNamedForm(Cours $cours,bool $add=true)
    {
        $this->cours = $cours;
        foreach ($stagiaires=$this->cours->getStagiaires() as $stagiaire) {
            echo $stagiaire->getNom().'<br>';
        }
        if($add) {
            $options = $this->initializeOptionsAdd();
            $name = 'add_stagiaire';
        } else {
            $name = 'remove_stagiaire';
            $options = $this->initializeRemove();
        }
        
        return $this->formFactory->createNamed($name,AddStagiaireCoursType::class,$this->cours,$options);
    }

    public function submitForm(FormInterface $form, Cours $cours,Request $request): bool
    {
        echo 'submitform:<br>';
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {           
            $this->addStagiaire($cours);   
            $this->em->persist($this->cours);
            $this->em->flush();
            return true;
        }
        return false;
    }

    private function addStagiaire(Cours $cours)
    {
            echo 'addstagiaire<br>';
            foreach ($stagiairesForm=$this->cours->getStagiaires() as $stagiaire) {
                echo 'add'.$stagiaire->getNom();
            }
            $stagiairesForm = $cours->getStagiaires('stagiaires');
            foreach ($stagiairesForm as $stagiaire) {
                if (!$this->cours->getStagiaires()->contains($stagiaire)) {
                    $this->cours->addStagiaire($stagiaire);
                }
            }
    }
}