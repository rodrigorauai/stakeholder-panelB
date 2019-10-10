<?php

namespace App\Repository;

use App\Entity\PaymentInvoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentInvoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentInvoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentInvoice[]    findAll()
 * @method PaymentInvoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentInvoiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentInvoice::class);
    }

    // /**
    //  * @return PaymentInvoice[] Returns an array of PaymentInvoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentInvoice
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
