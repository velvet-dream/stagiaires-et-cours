<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // var_dump($request->query);
        var_dump($request->query->get('title'));
        return $this->render('pages/mentionslegales.html.twig', [
            'title' => $request->query->get('title', 'Hervé'),
            // 'title' => (isset($_GET['title'])) ? $_GET['title']: 'fake page',
            // 'title' => $this->toto(),
        ]);
    }

    #[Route('/monom/{name}/{surname}', name: 'app_dynamique')]
    public function monNom( string $name = 'Lilya', string $surname = 'Emad') : Response
    {
        return $this->render('pages/mentionslegales.html.twig', [
            'title' => $name . " " . $surname,
        ]);
    } 
    

    #[Route('/json', name: 'app_json')]
    public function monJson() : JsonResponse
    {
        $data = [
            'name' => 'Lilya',
            'surname' => 'Drole',
        ];
        return $this->json($data);
    }

    #[Route('/download', name: 'app_download')]
    public function download() : BinaryFileResponse
    {
        return $this->file('download/test.txt');
    }

}
