<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController {

    #[Route('/test', name: 'app_test', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('pages/mentionslegales.html.twig', [
            'title' => $request->query->get('title','Hervé'),
        ]);
    }

    #[Route('/monnom/{name}/{surnom}', name: 'app_dynamique')]
    public function monNom(string $name = 'Hervé', string $surnom = 'HC'): Response
    {
        return $this->render('pages/mentionslegales.html.twig', [
            'title' => $name.' '.$surnom,
        ]);
    }

    #[Route('/json', name: 'app_json')]
    public function monJson(): JsonResponse
    {
        $datas = [
            'name' => 'Hervé',
            'surname' => 'Psychopathe',
        ];
        return $this->json($datas, 201);
    }

    #[Route('/download', name: 'app_download')]
    public function download(): BinaryFileResponse
    {
        return $this->file('download/test.txt');
    }
}