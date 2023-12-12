<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursFormType;
use App\Form\AddStagiaireCoursType;
use App\Repository\CoursRepository;
use App\Repository\StagiaireRepository;
use App\Services\FormCoursService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('cours/')]
class CoursController extends AbstractController
{
  #[Route('list', name: 'app_list_cours')]
  public function list(CoursRepository $coursRepo, Request $request): Response
  {
    // $cours = $coursRepo->searchByName($request->query->get('nom', ''));
    $cours = $coursRepo->findAll();

    return $this->render('cours/list.html.twig', [
      'title' => 'Liste des cours',
      'cours' => $cours,
      'nom' => $request->query->get('nom', ''),
    ]);
  }

  #[Route('show/{id}', name: 'app_show_cours')]
  public function show(?Cours $cours): Response
  {
    if ($cours === null) {
      return $this->redirectToRoute('app_list_cours');
    }
    return $this->render('cours/show.html.twig', [
      "title" => "Affichage du cours",
      "cours" => $cours,
    ]);
  }

  #[Route('new', name: 'app_new_cours')]
  public function new(Request $request, FormCoursService $formService): Response
  {
    $cours = new Cours();
    $form = $this->createForm(CoursFormType::class, $cours);

    $form->handleRequest($request);
    if ($formService->submitForm($form, $cours)) {
      return $this->redirectToRoute('app_list_cours');
    }

    return $this->render('cours/new.html.twig', [
      'title' => 'Création d\'un nouveau cours',
      'form' => $form
    ]);
  }

  #[Route('update/{id}', name: 'app_update_cours')]
  public function update(Request $request, FormCoursService $formService, ?Cours $cours)
  {
    if ($cours === null) {
      return $this->redirectToRoute('app_list_cours');
    }

    $form = $this->createForm(CoursFormType::class, $cours);

    $form->handleRequest($request);
    if ($formService->submitForm($form, $cours)) {
      return $this->redirectToRoute('app_list_cours');
    }

    return $this->render('cours/new.html.twig', [
      'title' => 'Modification d\'un cours',
      'form' => $form
    ]);
  }

  #[Route('addStagiaire/{id}', name: 'app_add_stagiaire_cours')]
  public function addStagiaire(
    Request $request, 
    EntityManagerInterface $em,
    ?Cours $cours,
    FormCoursService $formService
  ): Response
  {
    if ($cours === null) {
      return $this->redirectToRoute('app_list_cours');
    }
    
    $formAdd = $formService->createNamedForm($cours);
    $formRemove = $formService->createNamedForm($cours, false);

    if($request->request->has('remove_stagiaire')) {
      $formRemove->handleRequest($request);
      if ($formRemove->isSubmitted()) {
        $em->persist($cours);
        $em->flush();
        return $this->redirectToRoute('app_add_stagiaire_cours', ['id' => $cours->getId()]);
      }
    } elseif ($request->request->has('ajouter_stagiaire')) {
      $formAdd->handleRequest($request);
      if ($formAdd->isSubmitted()) {
        $em->persist($cours);
        $em->flush();
        return $this->redirectToRoute('app_add_stagiaire_cours', ['id' => $cours->getId()]);
      }
    }
    

    return $this->render('cours/gestion_stagiaires.html.twig', [
      'title' => 'Modification d\'un cours',
      'cours' => $cours,
      'form' => $formAdd,
      'formb' => $formRemove,
    ]);
  }
}
