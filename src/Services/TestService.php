<?php
namespace App\Services;

use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;

class TestService {

    public function __construct(
        private EntityManagerInterface $em
    )
    {

    }

    public function Affiche()
    {
        $cours = new Cours();
        $cours->setNom('javascript');
        $this->em->persist($cours);
        $this->em->flush();
        return 'je suis un texte';
    }
}