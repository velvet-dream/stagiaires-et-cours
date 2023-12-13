<?php
namespace App\Services;

use App\Entity\Cours;
use App\Entity\Stagiaire;
use App\Form\AddStagiaireCoursType;
use App\Repository\CoursRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormCoursService {
    private $cour;
    private $stagiaire;

    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory,
        private StagiaireRepository $stagiaireRepo,
        private CoursRepository $coursRepository,
    ) 
    {

    }

    private function initializeOptionsAdd()
    {
        return ['inscrits' => $this->stagiaireRepo->getStagiairesNotInscrit($this->cour), 'label_submit' => "Ajouter"];
    }

    private function initializeRemove()
    {
        return ['inscrits' => $this->cour->getStagiaires(), 'label_submit' => "Mettre à jour"];
    }
    /**
     * @param bool $add si true alors ajout stagiaire sinon remove_stagiaire
     */
    public function createNamedForm(Cours $cours,bool $add=true)
    {
        $this->cour = $cours;
        $this->stagiaire = $cours->getStagiaires();
        
;        if($add) {
            $options = $this->initializeOptionsAdd();
            $name = 'add_stagiaire';
        } else {
            $name = 'remove_stagiaire';
            $options = $this->initializeRemove();
        }
        
        return $this->formFactory->createNamed($name,AddStagiaireCoursType::class,$cours,$options);
    }

    public function submitForm(FormInterface $form, Cours $cours): bool
    {
       
        //$this->cour = $this->coursRepository->find($cours->getId());
        if ($form->isSubmitted() && $form->isValid())
        {           
            //$this->addStagiaire($cours);   
            $this->em->persist($this->cour);
            $this->em->flush();
            return true;
        }
        return false;
    }

    private function addStagiaire(Cours $cours)
    {
            var_dump('addstagiaire<br>');
            
            foreach ($this->cour->getStagiaires() as $stag) {
                var_dump('exists:'.$stag->getNom());
            }
            $stagiairesForm = $cours->getStagiaires('stagiaires');
            foreach ($stagiairesForm as $stagiaire) {
                var_dump('add:'.$stagiaire->getNom());
                if (!$this->cour->getStagiaires()->contains($stagiaire)) {
                    $this->cour->addStagiaire($stagiaire);
                }
            }
            die();
    }
}