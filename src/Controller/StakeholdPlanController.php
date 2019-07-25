<?php

namespace App\Controller;

use App\Entity\StakeholdPlan;
use App\Entity\StakeholdPlanReward;
use App\Form\StakeholdPlanRewardType;
use App\Form\StakeholdPlanSearchType;
use App\Form\StakeholdPlanType;
use App\Helper\ProfileHelper;
use App\Repository\StakeholdPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StakeholdPlanController extends AbstractController
{
    /**
     * @param ProfileHelper $profileSwitcher
     * @param Request $request
     * @param StakeholdPlanRepository $repository
     * @return Response
     * @Route("/planos-de-patrocinio", name="stakehold_plan__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT", "ROLE_STAKEHOLDER"})
     */
    public function index(ProfileHelper $profileSwitcher, Request $request, StakeholdPlanRepository $repository)
    {
        $plans = $repository->findAll();

        $form = $this->createForm(StakeholdPlanSearchType::class);
        $form->handleRequest($request);

        $currentProfile = $profileSwitcher->getCurrentProfile();
        switch ($currentProfile['id']) {

            case ProfileHelper::PROFILE_STAKEHOLDER:
                return $this->redirectToRoute('dashboard');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $plans = $repository->findUsingSearchForm($form);
        }

        return $this->render('stakehold_plan/index.html.twig', [
            'controller_name' => 'StakeholdingPlanController',
            'plans' => $plans,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/planos-de-patrocinio/adicionar", name="stakehold_plan__create")
     * @IsGranted({"ROLE_ADMINISTRATOR"})
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
     * @IsGranted({"ROLE_ADMINISTRATOR"})
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
     * @IsGranted({"ROLE_ADMINISTRATOR"})
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
     * @IsGranted({"ROLE_ADMINISTRATOR"})
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

    /**
     * @param StakeholdPlanReward $reward
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/planos-de-patrocinio/rendimentos/{id}", name="stakehold_plan__reward__edit")
     * @IsGranted({"ROLE_ADMINISTRATOR"})
     */
    public function editReward(StakeholdPlanReward $reward, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(StakeholdPlanRewardType::class, $reward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reward);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__reward__edit', ['id' => $reward->getId()], 303);
        }

        return $this->render('stakehold_plan/reward/form.html.twig', [
            'form' => $form->createView(),
            'plan' => $reward->getPlan(),
        ]);
    }
}
