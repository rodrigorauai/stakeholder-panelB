<?php

namespace App\Controller;

use App\Entity\Company;
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
}
