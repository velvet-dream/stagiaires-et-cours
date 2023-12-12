<?php

namespace App\Repository;

use App\Entity\Stagiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Stagiaire>
 *
 * @method Stagiaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stagiaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stagiaire[]    findAll()
 * @method Stagiaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);
    }

   public function searchByName($name, $firstName="", $trinom, $triprenom, $trinaissance): ?array
   {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom LIKE :val')
            ->andWhere('s.prenom LIKE :val2')
            ->setParameter('val', '%'.$name.'%' )
            ->setParameter('val2', '%'.$firstName.'%' )
            ->addOrderBy('s.nom', $trinom)
            ->addOrderBy('s.prenom', $triprenom)
            ->addOrderBy('s.date_naissance', $trinaissance)
            ->getQuery()
            ->getResult()
        ;
   }

   
   public function getStagiairesNotSubscribed($cours)
   {
    $sql = "SELECT * FROM stagiaire WHERE stagiaire.id NOT IN
        (SELECT c.stagiaire_id FROM stagiaire as s join `cours_stagiaire` as c
        ON s.id=c.stagiaire_id WHERE c.cours_id= ?);";
    $query = $this->getEntityManager()->getConnection()
        ->executeQuery($sql, [$cours->getId()]);
    $connards = $query->fetchAllAssociative();
    $results = [];
    foreach($connards as $notinscrit) {
        $results[] = $this->find($notinscrit['id']);
    }
    return $results;
   }

//    /**
//     * @return Stagiaire[] Returns an array of Stagiaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stagiaire
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
