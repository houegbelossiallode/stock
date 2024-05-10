<?php

namespace App\Repository;

use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vente>
 *
 * @method Vente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vente[]    findAll()
 * @method Vente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vente::class);
    }

//    /**
//     * @return Vente[] Returns an array of Vente objects
//     */
      public function search($article): array
   {
    return $this->createQueryBuilder('v')
                   ->andWhere('v.article LIKE :article')
                   ->setParameter('article', $article)
                   ->getQuery()
                   ->execute();
                   
   }



   public function getNb()
   {
       $qb = $this->createQueryBuilder('a')
           ->select('COUNT(a)');
       return(int) $qb->getQuery()->getSingleScalarResult();
   }

   

   
//    public function findOneBySomeField($value): ?Vente
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}