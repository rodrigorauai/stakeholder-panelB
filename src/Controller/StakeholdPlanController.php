<?php

namespace App\Controller;

use App\Entity\StakeholdPlan;
use App\Entity\StakeholdPlanReward;
use App\Form\StakeholdPlanRewardType;
use App\Form\StakeholdPlanType;
use App\Repository\StakeholdPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/planos-de-patrocinio/adicionar", name="stakehold_plan__create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(StakeholdPlanType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plan = $form->getData();

            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__index');
        }

        return $this->render('stakehold_plan/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param StakeholdPlan $plan
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("planos-de-patrocinio/{id}/dados-do-plano", name="stakehold_plan__edit")
     */
    public function edit(StakeholdPlan $plan, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(StakeholdPlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var StakeholdPlan $plan */
            $plan = $form->getData();

            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__edit', ['id' => $plan->getId()], 303);
        }

        return $this->render('stakehold_plan/edit.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param StakeholdPlan $plan
     * @return Response
     * @Route("planos-de-patrocinio/{id}/rendimentos", name="stakehold_plan__reward__index")
     */
    public function rewardIndex(StakeholdPlan $plan)
    {
        return $this->render('stakehold_plan/reward/index.html.twig', [
            'plan' => $plan,
        ]);
    }

    /**
     * @param StakeholdPlan $plan
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/planos-de-patrocinio/{id}/rendimentos/novo", name="stakehold_plan__reward__create")
     */
    public function createReward(StakeholdPlan $plan, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(StakeholdPlanRewardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var StakeholdPlanReward $reward */
            $reward = $form->getData();
            $reward->setPlan($plan);

            $entityManager->persist($reward);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__reward__index', ['id' => $plan->getId()], 303);
        }

        return $this->render('stakehold_plan/reward/form.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }
}