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
 
class StagiairesController extends AbstractController
{
    #[Route ('/stagiaires' , name : 'app_stagiaires')]
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
    #[Route ('/stagiaires/new' , name : 'app_newstagiaire')]
    public function new(): Response
    {
        $form = $this->createForm(StagiaireFormType::class);

        return $this->render('stagiaires/new.html.twig' , [
            'title' => 'Création d\'une nouvelle entrée',
            'form' => $form
        ]);
        // $stagiaire = new Stagiaire();
        // $stagiaire->setNom('Coffin');
        // $stagiaire->setPrenom('Alice');

        // // tell Doctrine you want to (eventually) save the stagiaire (no queries yet)
        // $entityManager->persist($stagiaire);

        // // actually executes the queries (i.e. the INSERT query)
        // $entityManager->flush();

        // return new Response('Saved new stagiaire with id '.$stagiaire->getId());
    }
 
    #[Route ('/stagiaires/{id}' , name : 'app_show_stagiaire')]
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
 
    #[Route ('/stagiaire/search/{nom}' , name : 'app_search_stagiaire')]
    public function search (StagiaireRepository $repo, string $nom = null) : Response
    {
       
        if ( $nom === null ){
            return $this->redirectToRoute('app_index');
        }
        $results = $repo->searchByName($nom);
        return $this->render('stagiaires/list.html.twig' , [
            'title' => 'Recherche de' .$nom,
            'stagiaire' => $results,
        ]);
    }
 
}
