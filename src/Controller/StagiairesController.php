<?php
namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireFormType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('stagiaire/')]
class StagiairesController extends AbstractController {

    #[Route('list', name: 'app_list_stagiaires')]
    public function list(StagiaireRepository $stagiairesRepo,Request $request): Response
    {
        $trinom = $request->query->get('trinom','asc');
        $triprenom = $request->query->get('triprenom','asc');
        $stagiaires = $stagiairesRepo->searchByName($request->query->get('nom',''), $trinom, $triprenom);

        return $this->render('stagiaires/list.html.twig', [
            'title' => 'Liste des stagiaires',
            'stagiaires' => $stagiaires,
            'trinom' => $trinom,
            'triprenom' => $triprenom,
            'nom' => $request->query->get('nom',''),
        ]);
    }

    #[Route('show/{id}', name: 'app_show_stagiaire')]
    public function show(?Stagiaire $stagiaire): Response
    {
        if ($stagiaire === null) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('stagiaires/show.html.twig', [
            'title' => 'Fiche d\'un stagiaire',
            'stagiaire' => $stagiaire,
        ]);
    }

    #[Route('new', name: 'app_new_stagiaire')]
    public function new(Request $request, EntityManagerInterface $em, Security $security):Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_list_stagiaires');
        }
        $stagiaire = new Stagiaire();
        $form = $this->createForm(StagiaireFormType::class, $stagiaire);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('app_list_stagiaires');
        }
        return $this->render('stagiaires/new.html.twig',[
            'title' => 'Création d\'un nouvel utilisateur',
            'form' => $form,
        ]);
    }

    #[Route('update/{id}', name: 'app_update_stagiaire')]
    public function update(
        Request $request, 
        EntityManagerInterface $em, 
        ?Stagiaire $stagiaire,
        Security $security)
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_list_stagiaires');
        }
        if ($stagiaire === null) {
            return $this->redirectToRoute('app_list_stagiaires');
        }

        $form = $this->createForm(StagiaireFormType::class, $stagiaire);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('app_list_stagiaires');
        }
        return $this->render('stagiaires/new.html.twig',[
            'title' => 'Mise à jour d\'un utilisateur',
            'form' => $form,
        ]);
    }
}