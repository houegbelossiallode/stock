<?php

namespace App\Repository;

use App\Entity\QuantiteAjoute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuantiteAjoute>
 *
 * @method QuantiteAjoute|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantiteAjoute|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantiteAjoute[]    findAll()
 * @method QuantiteAjoute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantiteAjouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantiteAjoute::class);
    }

//    /**
//     * @return QuantiteAjoute[] Returns an array of QuantiteAjoute objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuantiteAjoute
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
