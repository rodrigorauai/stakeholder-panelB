<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Person;
use App\Form\ContractType;
use App\Form\ContractSearchType;
use App\Form\ContractTypeUSN;
use App\Form\SearchTypeUSN;
use App\Helper\ProfileHelper;
use App\Helper\UploadHelper;
use App\Repository\ContractRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\TranslateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @param Request $request
     * @param ProfileHelper $profileHelper
     * @param ContractRepository $repository
     * @param ConfigurationRepository $crepository
     * @param TranslateRepository $transrepository
     * @return Response
     */
    public function index(Request $request, ProfileHelper $profileHelper, ContractRepository $repository, ConfigurationRepository $crepository, TranslateRepository $transrepository)
    {
        /** @var Person $user */
        $user = $this->getUser();
        $profile = $profileHelper->getCurrentProfile();

        $multipleOwners = false;

        if ($profile['id'] === ProfileHelper::PROFILE_STAKEHOLDER) {
            $accounts = $user->getAccounts();

            if (count($accounts) > 1) {
                $multipleOwners = true;
            }
        }

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(SearchTypeUSN::class);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(ContractSearchType::class);
                $form->handleRequest($request);
            }
        }

        $contracts = $repository->findUsingSearchForm($form, $accounts ?? null);

        $currency = $crepository->findOneByActive();

        return $this->render('contract/index.html.twig', [
            'translates' => $disableds,
            'currency' => $currency->getLabel(),
            'contracts' => $contracts,
            'multipleOwners' => $multipleOwners,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Contract $contract
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UploadHelper $helper
     * @return RedirectResponse|Response
     * @Route("/contratos-de-patrocinio/{id}", name="contract__edit")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function edit(Contract $contract, Request $request, EntityManagerInterface $entityManager, UploadHelper $helper,
                         TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(ContractTypeUSN::class, $contract);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(ContractType::class, $contract);
                $form->handleRequest($request);
            }
        }


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
            'translates' => $disableds,
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
