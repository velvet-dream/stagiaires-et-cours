<?php

namespace App\Controller;
 
use App\Entity\Stagiaire;
use App\Form\StagiaireFormType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route ('stagiaire/')]
class StagiairesController extends AbstractController
{
    #[Route ('list' , name : 'app_stagiaires')]
    public function list(StagiaireRepository $stagiairesRepo, Request $request) : Response
    {
        $trinom = $request->query->get('trinom', 'asc');
        $triprenom = $request->query->get('triprenom', 'asc');
        $trinaissance = $request->query->get('trinaissance', 'asc');
        $stagiaires = $stagiairesRepo->searchByName($request->query->get('nom', ''), $request->query->get('prenom', ''), $trinom, $triprenom, $trinaissance);
        
        return $this->render('stagiaires/list.html.twig' , [
            'title' => 'Liste des stagiaires',
            'stagiaires' => $stagiaires,
            'trinom' => $trinom,
            'triprenom' => $triprenom,
            'trinaissance' => $trinaissance,
            'nom' => $request->query->get('nom', ''),
            'prenom' => $request->query->get('prenom', ''),
        ]);
    }

    // TEST
    #[Route ('new' , name : 'app_newstagiaire')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $stagiaire = new Stagiaire();
        $form = $this->createForm(StagiaireFormType::class, $stagiaire);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('app_stagiaires');
        }

        return $this->render('stagiaires/new.html.twig' , [
            'title' => 'Création d\'une nouvelle entrée',
            'form' => $form
        ]);
    }
 
    #[Route ('show/{id}' , name : 'app_show_stagiaire')]
    public function show(?Stagiaire $stagiaire) : Response
    {
        // var_dump($stagiaire);
        if ($stagiaire === null ){
            return $this->redirectToRoute('app_index');
        }
 
        return $this->render('stagiaires/show.html.twig' , [
            'title' => 'Fiche d\'un stagiaire',
            'stagiaire' => $stagiaire,
        ]);
    }
 
    #[Route ('search/{nom}' , name : 'app_search_stagiaire')]
    public function search (StagiaireRepository $repo, string $nom = null) : Response
    {
       
        if ( $nom === null ){
            return $this->redirectToRoute('app_index');
        }
        $results = $repo->searchByName($nom,"","","","");
        return $this->render('stagiaires/list.html.twig' , [
            'title' => 'Recherche de' .$nom,
            'stagiaire' => $results,
        ]);
    }

    #[Route ('update/{id}', name: 'app_update_stagiaire')]
    public function update(Request $request, EntityManagerInterface $em, ?Stagiaire $stagiaire)
    {
        if ( $stagiaire === null ){
            return $this->redirectToRoute('app_stagiaires');
        }

        $form = $this->createForm(StagiaireFormType::class, $stagiaire);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('app_stagiaires');
        }

        return $this->render('stagiaires/new.html.twig' , [
            'title' => 'Modification d\'une entrée',
            'form' => $form
        ]);
    }
 
}
