<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursFormType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isNull;

#[Route('cours/')]
class CoursController extends AbstractController
{
  #[Route('list', name: 'app_list_cours')]
  public function list(CoursRepository $coursRepo, Request $request): Response
  {
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
  public function new(Request $request, EntityManagerInterface $em): Response
  {
    $cours = new Cours();
    $form = $this->createForm(CoursFormType::class, $cours);

    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $em->persist($cours);
      $em->flush();
      return $this->redirectToRoute('app_list_cours');
    }

    return $this->render('cours/new.html.twig', [
      'title' => 'Création d\'un nouveau cours',
      'form' => $form
    ]);
  }

  #[Route('update/{id}', name: 'app_update_cours')]
  public function update(Request $request, EntityManagerInterface $em, ?Cours $cours)
  {
    if ($cours === null) {
      return $this->redirectToRoute('app_list_cours');
    }

    $form = $this->createForm(CoursFormType::class, $cours);

    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $em->persist($cours);
      $em->flush();
      return $this->redirectToRoute('app_list_cours');
    }

    return $this->render('cours/new.html.twig', [
      'title' => 'Modification d\'un cours',
      'form' => $form
    ]);
  }
}
