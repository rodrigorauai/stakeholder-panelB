<?php

namespace App\Repository;

use App\Entity\UploadedPersonFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedPersonFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedPersonFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedPersonFile[]    findAll()
 * @method UploadedPersonFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedPersonFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedPersonFile::class);
    }

    // /**
    //  * @return UploadedPersonFile[] Returns an array of UploadedPersonFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UploadedPersonFile
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
