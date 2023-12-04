<?php

namespace App\Repository;

use App\Entity\Ratingsaif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ratingsaif>
 *
 * @method Ratingsaif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ratingsaif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ratingsaif[]    findAll()
 * @method Ratingsaif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingsaifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ratingsaif::class);
    }

//    /**
//     * @return Ratingsaif[] Returns an array of Ratingsaif objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ratingsaif
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }





public function findRating(int $idUser, int $eventId): ?Ratingsaif
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.idUser = :idUser')
        ->leftJoin('r.eventR', 'e') // Assuming 'eventR' is the property in Ratingsaif representing the ManyToOne relationship
        ->andWhere('e.id = :eventId')
        ->setParameter('idUser', $idUser)
       ->setParameter('eventId', $eventId)
        ->getQuery()
        ->getOneOrNullResult();
}



}
