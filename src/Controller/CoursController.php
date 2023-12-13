<?php
namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursFormType;
use App\Form\AddStagiaireCoursType;
use App\Repository\CoursRepository;
use App\Repository\StagiaireRepository;
use App\Services\FormCoursService;
use App\Services\TestService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('cours/')]
class CoursController extends AbstractController {

    #[Route('list', name: 'app_list_cours')]
    public function list(CoursRepository $coursRepo,Request $request): Response
    {
        $cours = $coursRepo->findAll();

        return $this->render('cours/list.html.twig', [
            'title' => 'Liste des cours',
            'cours' => $cours,
        ]);
    }

    #[Route('new', name: 'app_new_cours')]
    public function create(
        Request $request,
        FormCoursService $formCoursService,
        Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            $this->redirectToRoute('app_index');
        }

        //$user = $security->getUser();

        $cours = new Cours;
        $form = $this->createForm(CoursFormType::class, $cours);

        $form->handleRequest($request);
        if($formCoursService->submitForm($form,$cours,$request)) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('cours/new.html.twig',[
            'title' => 'Création d\'un cours',
            'form' => $form,
        ]);
    }

    #[Route('update/{id}', name: 'app_update_cours')]
    public function update(
        Request $request,  
        ?Cours $cours,
        FormCoursService $formCoursService): Response
    {
        if($cours === null) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(CoursFormType::class, $cours);

        $form->handleRequest($request);
        if($formCoursService->submitForm($form,$cours,$request)) {
            return $this->redirectToRoute('app_index');
        }
        
        return $this->render('cours/new.html.twig',[
            'title' => 'Création d\'un cours',
            'form' => $form,
        ]);
    }

    #[Route('addstagiaire/{id}', name: 'app_addstagiaire_cours')]
    public function addStagiaire(
        Request $request, 
        ?Cours $cours, 
        FormCoursService $formCoursService): Response       
    {
        if($cours === null) {
            return $this->redirectToRoute('app_index');
        }        
        
        $formadd = $formCoursService->createNamedForm($cours);
        $formRemove = $formCoursService->createNamedForm($cours, false);
        /**if($request->request->has('remove_stagiaire')) {
            $formRemove->handleRequest($request);
            if($formRemove->isSubmitted()) {
                $em->persist($cours);
                $em->flush();
                return $this->redirectToRoute('app_addstagiaire_cours', ['id' => $cours->getId()]);
            }
        }**/

        if($formCoursService->submitForm($formadd,$cours,$request)) {
            return $this->redirectToRoute('app_addstagiaire_cours', ['id' => $cours->getId()]);
        }

        return $this->render('cours/gestionstagiaires.html.twig',[
            'title' => "Gestion des inscrits au cours ".$cours->getNom(),
            'formadd' => $formadd,
            'formremove' => $formRemove,
        ]);
    }
}