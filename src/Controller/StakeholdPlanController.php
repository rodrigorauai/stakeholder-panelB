<?php

namespace App\Controller;

use App\Repository\StakeholdPlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StakeholdPlanController extends AbstractController
{
    /**
     * @param StakeholdPlanRepository $repository
     * @return Response
     * @Route("/planos-de-patrocinio", name="stakehold_plan__index")
     */
    public function index(StakeholdPlanRepository $repository)
    {
        $plans = $repository->findAll();

        return $this->render('stakehold_plan/index.html.twig', [
            'controller_name' => 'StakeholdingPlanController',
            'plans' => $plans,
        ]);
    }
}
