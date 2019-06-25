<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Company;
use App\Form\AddressType;
use App\Form\CompanyData;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @param CompanyRepository $repository
     * @return Response
     * @Route("/empresas", name="company__index")
     */
    public function index(CompanyRepository $repository)
    {
        $companies = $repository->findAll();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/empresas/adicionar", name="company__create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CompanyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = Company::fromDataObject($form->getData());

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
     */
    public function edit(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CompanyType::class, CompanyData::fromEntity($company));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->updateFromDataObject($form->getData());

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
}
