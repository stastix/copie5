<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
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

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



public function findStudentByEventIdAndPrixR($eventId)
{
    return $this->createQueryBuilder('r')
        ->select('r') 
        ->where('r.eventId = :eventId')
        ->setParameter('eventId', $eventId)
        ->getQuery()
        ->getResult();
}

public function findStudentByEventIdAndPrixR4($eventId): array
{
    return $this->createQueryBuilder('r')
          ->andWhere('r.prixR > :prixR')
        ->setParameter('prixR', $eventId)
        ->getQuery()
        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY); 
}
public function findStudentByEventIdAndPrixR3($eventId): array
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.eventId = :eventId')
        ->setParameter('eventId', $eventId)
        ->getQuery()
        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY); 
}

public function findStudentByEventIdAndPrixR2($eventId, $prixR): array
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.eventId = :eventId')
        ->andWhere('r.prixR > :prixR')
        ->setParameters([
            'eventId' => $eventId,
            'prixR' => $prixR,
        ])
        ->getQuery()
        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
}
}
