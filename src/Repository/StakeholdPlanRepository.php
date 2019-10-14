<?php

namespace App\Repository;

use App\Entity\StakeholdPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method StakeholdPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method StakeholdPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method StakeholdPlan[]    findAll()
 * @method StakeholdPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StakeholdPlanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StakeholdPlan::class);
    }

    /**
     * @param FormInterface $form
     * @return StakeholdPlan[]
     */
    public function findUsingSearchForm(FormInterface $form)
    {
        $qb = $this->createQueryBuilder('plan');
        $qb
            ->select('plan')
            ->orderBy('plan.id', 'DESC');

        if ($form->isSubmitted() && $form->isValid()) {
            $qb
                ->where(
                    $qb->expr()->orX(
                        $qb->expr()->eq('plan.id', ':queryString'),
                        $qb->expr()->like('plan.administrativeName', ':containingQueryString'),
                        $qb->expr()->like('plan.commercialName', ':containingQueryString')
                    )
                )
                ->setParameters([
                    'queryString' => $form->get('queryString')->getData(),
                    'containingQueryString' => '%'.$form->get('queryString')->getData().'%',
                ]);
        }

        return $qb->getQuery()->getResult();
    }
}
