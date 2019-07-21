<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Helper\UploadHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @param UploadHelper $helper
     * @return RedirectResponse|Response
     * @Route("/contratos-de-patrocinio/{id}", name="contract__edit")
     */
    public function edit(Contract $contract, Request $request, EntityManagerInterface $entityManager, UploadHelper $helper)
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contract $contract */
            $contract = $form->getData();

            $file = $form->get('contractFile')->getData();

            if ($file) {
                $helper->saveDigitizedContract($file, $contract);
            }

            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('contract__edit', ['id' => $contract->getId()], 303);
        }

        return $this->render('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Contract $contract
     * @return BinaryFileResponse
     * @Route("/contratos-de-patrocinio/{id}/contrato-digitalizado", name="contract__digitized_contract__download")
     */
    public function downloadDigitizedContract(Contract $contract)
    {
        if ($contract->getDigitizedContracts()->count() === 0) {
            throw new NotFoundHttpException();
        }

        return new BinaryFileResponse($contract->getDigitizedContracts()->last()->getPath());
    }
}
