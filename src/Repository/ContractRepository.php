<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    /**
     * @return Contract[]
     */
    public function findAll()
    {
        return $this
            ->createQueryBuilder('contract')
            ->orderBy('contract.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $accounts
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function calculateTotalInvestment(array $accounts)
    {
        $qb = $this->createQueryBuilder('contract');
        $qb->select('SUM(contract.value)');

        if (count($accounts) > 0) {
            $qb
                ->where(
                    $qb->expr()->in('contract.account', ':accounts')
                )
                ->setParameter('accounts', $accounts)
            ;
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByAccount(array $accounts)
    {
        $qb = $this->createQueryBuilder('contract');
        $qb
            ->where($qb->expr()->in('contract.account', ':accounts'))
            ->setParameter('accounts', $accounts)
            ->orderBy('contract.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $qb->getQuery()->getResult();
    }
}
