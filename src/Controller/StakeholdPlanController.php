<?php

namespace App\Controller;

use App\Entity\StakeholdPlan;
use App\Entity\StakeholdPlanReward;
use App\Form\SearchTypeUSN;
use App\Form\StakeholdPlanRewardType;
use App\Form\StakeholdPlanRewardTypeUSN;
use App\Form\StakeholdPlanSearchType;
use App\Form\StakeholdPlanType;
use App\Form\StakeholdPlanTypeUSN;
use App\Repository\StakeholdPlanRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\TranslateRepository;
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
     * @param Request $request
     * @param StakeholdPlanRepository $repository
     * @param ConfigurationRepository $crepository
     * @param TranslateRepository $transrepository
     * @return Response
     * @Route("/planos-de-patrocinio", name="stakehold_plan__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function index(Request $request, StakeholdPlanRepository $repository, ConfigurationRepository $crepository, TranslateRepository $transrepository)
    {
        $plans = $repository->findAll();

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(SearchTypeUSN::class);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(StakeholdPlanSearchType::class);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $plans = $repository->findUsingSearchForm($form);
        }

        $currency = $crepository->findOneByActive();

        return $this->render('stakehold_plan/index.html.twig', [
            'translates' => $disableds,
            'currency' => $currency->getLabel(),
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
    public function create(Request $request, EntityManagerInterface $entityManager, TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(StakeholdPlanTypeUSN::class);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(StakeholdPlanType::class);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $plan = $form->getData();

            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__index');
        }

        return $this->render('stakehold_plan/form.html.twig', [
            'translates' => $disableds,
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
    public function edit(StakeholdPlan $plan, Request $request, EntityManagerInterface $entityManager,
                         TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(StakeholdPlanTypeUSN::class, $plan);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(StakeholdPlanType::class, $plan);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var StakeholdPlan $plan */
            $plan = $form->getData();

            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__edit', ['id' => $plan->getId()], 303);
        }

        return $this->render('stakehold_plan/edit.html.twig', [
            'translates' => $disableds,
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    public function editTranslate(TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        return $this->render('stakehold_plan/_tab-bar.html.twig', [
            'translates' => $disableds,
        ]);
    }

    /**
     * @param StakeholdPlan $plan
     * @return Response
     * @Route("planos-de-patrocinio/{id}/rendimentos", name="stakehold_plan__reward__index")
     * @IsGranted({"ROLE_ADMINISTRATOR"})
     */
    public function rewardIndex(StakeholdPlan $plan, TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());


        return $this->render('stakehold_plan/reward/index.html.twig', [
            'translates' => $disableds,
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
    public function createReward(StakeholdPlan $plan, Request $request, EntityManagerInterface $entityManager,
        TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(StakeholdPlanRewardTypeUSN::class);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(StakeholdPlanRewardType::class);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var StakeholdPlanReward $reward */
            $reward = $form->getData();
            $reward->setPlan($plan);

            $entityManager->persist($reward);
            $entityManager->flush();

            return $this->redirectToRoute('stakehold_plan__reward__index', ['id' => $plan->getId()], 303);
        }

        return $this->render('stakehold_plan/reward/form.html.twig', [
            'translates' => $disableds,
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
