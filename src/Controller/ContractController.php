<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\ContractType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractController extends AbstractController
{
    /**
     * @Route("/contract", name="contract_index")
     */
    public function index()
    {
        return $this->render('contract/index.html.twig', [
            'controller_name' => 'ContractController',
        ]);
    }

    /**
     * @param Contract $contract
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("/contratos-de-patrocinio/{id}", name="contract__edit")
     */
    public function edit(Contract $contract, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contract $contract */
            $contract = $form->getData();

            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('contract__edit', ['id' => $contract->getId()], 303);
        }

        return $this->render('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
        ]);
    }
}
