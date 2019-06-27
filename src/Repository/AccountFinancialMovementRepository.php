<?php

namespace App\Repository;

use App\Entity\AccountFinancialMovement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccountFinancialMovement|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountFinancialMovement|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountFinancialMovement[]    findAll()
 * @method AccountFinancialMovement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountFinancialMovementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccountFinancialMovement::class);
    }

    // /**
    //  * @return AccountFinancialMovement[] Returns an array of AccountFinancialMovement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountFinancialMovement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
