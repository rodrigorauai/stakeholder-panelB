<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyData;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
