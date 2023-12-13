<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminStagiairesController extends AbstractController
{
    #[Route('/stagiaires', name: 'app_admin_stagiaires')]
    public function index(): Response
    {
        return $this->render('admin/stagiaires/index.html.twig', [
            'title' => 'AdminStagiairesController',
        ]);
    }
}
