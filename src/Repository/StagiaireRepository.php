<?php

namespace App\Repository;

use App\Entity\Stagiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function searchByName(string $name, string $trinom, string $triprenom): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.nom like :val')
            ->setParameter('val', '%'.$name.'%')
            ->addOrderBy('s.nom', $trinom)
            ->addOrderBy('s.prenom', $triprenom)
            ->getQuery()
            ->getResult();
    }

    public function getStagiairesNotInscrit($cours)
    {
        $sql = "select * from stagiaire where stagiaire.id 
        NOT IN (SELECT c.stagiaire_id FROM stagiaire as s join `cours_stagiaire` as c  on s.id=c.stagiaire_id where c.cours_id= ?);";
        $query = $this->getEntityManager()->getConnection()
                ->executeQuery($sql, [$cours->getId()]);
        $result = $query->fetchAllAssociative();
        $stagiaires = [];
        foreach($result as $notinscrit) {
            $stagiaire = $this->find($notinscrit['id']);
            $stagiaires[] = $stagiaire;
        }
        return $stagiaires;
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
