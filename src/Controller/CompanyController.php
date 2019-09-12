<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Address;
use App\Entity\BankAccount;
use App\Entity\Company;
use App\Entity\UploadedCompanyFile;
use App\Form\AddressType;
use App\Form\BankAccountType;
use App\Form\CompanyData;
use App\Form\CompanySearchType;
use App\Form\CompanyType;
use App\Form\CompanyTypeNew;
use App\Form\FileUploadType;
use App\Helper\UploadHelper;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @param Request $request
     * @param CompanyRepository $repository
     * @return Response
     * @Route("/empresas", name="company__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function index(Request $request, CompanyRepository $repository)
    {
        $companies = $repository->findAll();

        $form = $this->createForm(CompanySearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companies = $repository->findByExampleField($form);
        }

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/empresas/adicionar", name="company__create")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CompanyTypeNew::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = Company::fromDataObject($form->getData());
            $account = new Account($company);

            $entityManager->persist($account);
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company__index');
        }

        return $this->render('company/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Company $company
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/empresas/{id}/editar", name="company__edit")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function edit(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CompanyType::class, CompanyData::fromEntity($company));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->updateFromDataObject($form->getData());
            $company->setCnpj(str_replace(['.', '/', '-'], '', $company->getCnpj()));

            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company__edit', ['id' => $company->getId()], 303);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Company $company
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/empresas/{id}/endereço", name="company_address__edit")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function editAddress(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AddressType::class, $company->getAddress());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Address $address */
            $address = $form->getData();

            if (!$address->hasEntity()) {
                $address->setEntity($company);
            }

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('company_address__edit', ['id' => $company->getId()], 303);
        }

        return $this->render('company/edit--address.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Company $company
     * @return Response
     * @Route("/company/{id}/contas-de-patrocinio", name="company_account__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function showAccounts(Company $company)
    {
        return $this->render('company/show--accounts.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @param Company $company
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("/empresas/{id}/conta-bancaria", name="company__bank_account__edit")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function editBankAccount(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        $account = $company->getBankAccount();

        $form = $this->createForm(BankAccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BankAccount $account */
            $account = $form->getData();
            $account->setOwner($company);

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('company__bank_account__edit', ['id' => $company->getId()], 303);
        }

        return $this->render('company/bank-account/edit.html.twig', [
            'company' => $company,
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Company $company
     * @return Response
     * @Route("/company/{id}/arquivos", name="company__file__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function uploadIndex(Company $company)
    {
        return $this->render('company/file/index.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @param Company $company
     * @param Request $request
     * @param UploadHelper $helper
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("/empresas/{id}/arquivos/novo", name="company__file__form")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function uploadForm(Company $company, Request $request, UploadHelper $helper, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $helper->saveCompanyFile($data['name'], $data['file'], $company);

            // Flush entity created by the UploadHelper
            $entityManager->flush();

            return $this->redirectToRoute('company__file__index', ['id' => $company->getId()], 303);
        }

        return $this->render('company/file/form.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param UploadedCompanyFile $file
     * @return BinaryFileResponse
     * @Route("/company/arquivos/{id}", name="company__file__download")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function fileDownload(UploadedCompanyFile $file)
    {
        return new BinaryFileResponse($file->getPath());
    }
}
