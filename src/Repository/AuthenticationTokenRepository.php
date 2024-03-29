<?php

namespace App\Repository;

use App\Entity\AuthenticationToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuthenticationToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthenticationToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthenticationToken[]    findAll()
 * @method AuthenticationToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthenticationTokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthenticationToken::class);
    }

    // /**
    //  * @return AuthenticationToken[] Returns an array of AuthenticationToken objects
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
    public function findOneBySomeField($value): ?AuthenticationToken
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
