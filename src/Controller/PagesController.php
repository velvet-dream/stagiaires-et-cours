<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'title' => 'Mon premier controller symfony',
            'controller_var' => 'LET S GOOOOO',
            'message' => 'New liste (de course)',
        ]);
    }

    #[Route('/mentionslegales', name: 'app_mentionslegales')]
    public function mentionsLegales(): Response
    {
        // return $this->redirectToRoute('app_index');
        return $this->render('pages/mentionslegales.html.twig', [
            'title' => 'Mentions légales',
        ]);
    }
}
