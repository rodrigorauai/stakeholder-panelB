<?php

namespace App\Repository;

use App\Entity\UploadedCompanyFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedCompanyFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedCompanyFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedCompanyFile[]    findAll()
 * @method UploadedCompanyFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedCompanyFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedCompanyFile::class);
    }

    // /**
    //  * @return UploadedCompanyFile[] Returns an array of UploadedCompanyFile objects
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
    public function findOneBySomeField($value): ?UploadedCompanyFile
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
