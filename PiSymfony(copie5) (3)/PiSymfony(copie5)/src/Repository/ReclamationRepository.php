<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
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

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function showreclamationandUser()
{
    return $this->createQueryBuilder('r')
    ->join('r.UseName', 'u')
    ->addSelect('u')
    ->getQuery()
    ->getResult();
}
public function showreclamationandUser2()
{
    return $this->createQueryBuilder('r')
    ->join('r.UseName', 'u')
    ->addSelect('u')
    ->getQuery()
    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
}
public function searchByCible($cible)
{
    return $this->createQueryBuilder('r')
        ->join('r.UseName', 'u')
        ->addSelect('u')           
        ->andWhere("r.CibleReclamation = :cible")
        ->setParameter('cible', $cible)
        ->getQuery()
        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
}

public function showreclamationandUser3()
{
    return $this->createQueryBuilder('r')
    ->join('r.UseName', 'u')
    ->addSelect('u');

}


}
