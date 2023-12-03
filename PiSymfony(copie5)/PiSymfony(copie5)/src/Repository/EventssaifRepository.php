<?php

namespace App\Repository;

use App\Entity\Eventssaif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eventssaif>
 *
 * @method Eventssaif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eventssaif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eventssaif[]    findAll()
 * @method Eventssaif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventssaifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eventssaif::class);
    }

//    /**
//     * @return Eventssaif[] Returns an array of Eventssaif objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Eventssaif
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findBychoix($a, $b)
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.destinationsaif = :value')
        ->andWhere('e.typesaif = :value2')  
        ->setParameter('value', $a)
        ->setParameter('value2', $b)
        ->getQuery()
        ->getResult();
}


public function findAllDistinct()
{
    return $this->createQueryBuilder('e')
     ->select('DISTINCT e.destinationsaif, e.typesaif')

    ->getQuery()
    ->getResult();


}

public function findAllDistinctTypes(): array
{
    $qb = $this->createQueryBuilder('e');
    $qb->select('DISTINCT e.typesaif');

    $result = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

    // Extract the distinct types from the result
    $distinctTypes = array_column($result, 'typesaif');

    return $distinctTypes;
}
public function findAllDistinctDestination(): array
{
    $qb = $this->createQueryBuilder('e');
    $qb->select('DISTINCT e.destinationsaif');

    $result = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

    // Extract the distinct types from the result
    $distinctTypes = array_column($result, 'destinationsaif');

    return $distinctTypes;
}



}