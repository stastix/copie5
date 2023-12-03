<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenements>
 *
 * @method Evenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenements[]    findAll()
 * @method Evenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

    public function findLowBudgetEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.prix', 'ASC') 
            ->setMaxResults(5) 
            ->getQuery()
            ->getResult();
    }

public function findClosestEvent(): ?Evenements
{
    return $this->createQueryBuilder('e')
        ->where('e.dateDepart >= :currentDate')
        ->setParameter('currentDate', new \DateTime('now'))
        ->orderBy('e.dateDepart', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}


public function findByCriteria($criteria, $searchValue): array
{
    $qb = $this->createQueryBuilder('e');
    switch ($criteria) {
        case 'destination':
            $qb->andWhere($qb->expr()->like('e.destination', ':destination'))
               ->setParameter('destination', '%' . $searchValue . '%');
            break;
        case 'typeevenement':
            $qb->andWhere($qb->expr()->like('e.typeevenement', ':type'))
               ->setParameter('type', '%' . $searchValue . '%'); 
            break;
        case 'titre':
            $qb->andWhere($qb->expr()->like('e.titre', ':titre'))
               ->setParameter('titre', '%' . $searchValue . '%');
            break;
        default:
            throw new \InvalidArgumentException('Critère de recherche invalide');
    }

    $query = $qb->getQuery();
    $results = $query->getResult();

    return $results;
}





}

//    /**
//     * @return Evenements[] Returns an array of Evenements objects
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

//    public function findOneBySomeField($value): ?Evenements
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

