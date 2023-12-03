<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    
    public function getCommandeDetailsForProductId1($usertId)
    {
        return $this->createQueryBuilder('c')
        ->select('c.id', 'c.userId', 'COUNT(c.productId) AS quantity', 'c.Comfirmed') 
        ->groupBy('c.productId', 'c.userId')
        ->having('c.userId = :userId')
            ->setParameter('userId', $usertId)
            ->getQuery()
            ->getResult();
    }
    /*
    public function getCommandeDetailsForProductId1()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id', 'c.userId', 'COUNT(c.productId) AS quantity', 'c.Comfirmed') 
            ->groupBy('c.productId', 'c.userId')
            ->getQuery()
            ->getResult();
    }
    */



    public function getUnconfirmedCommandeDetailsForProductId1()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id', 'c.userId', 'COUNT(c.productId) AS quantity' )
            ->where('c.Comfirmed != :confirmedValue')
            ->setParameter('confirmedValue', true)
            ->groupBy('c.productId', 'c.userId')
           ->getQuery()
            ->getResult();
    }


}
