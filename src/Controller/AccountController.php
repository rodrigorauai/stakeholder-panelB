<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Contract;
use App\Form\ContractType;
use App\Helper\UploadHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account_index")
     * @IsGranted({"ROLE_ADMINISTRATOR"})
     */
    public function index()
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }


    /**
     * @param Account $account
     * @return Response
     * @Route("/contas-de-patrocinio/{id}", name="account__show")
     * @IsGranted({"ROLE_ADMINISTRATOR"})
     */
    public function show(Account $account)
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * @param Account $account
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UploadHelper $helper
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/contas-de-patrocinio/{id}/contratos/novo", name="account__contract__create")
     * @IsGranted({"ROLE_ADMINISTRATOR"})
     */
    public function createContract(Account $account, Request $request, EntityManagerInterface $entityManager, UploadHelper $helper)
    {
        $defaultExpiration = new DateTime('+ 1 year');
        $defaultExpiration->modify('last day of this month');

        $contract = new Contract(null, null, new DateTime(), null, $defaultExpiration);

        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contract $contract */
            $contract = $form->getData();
            $contract->setAccount($account);

            $file = $form->get('contractFile')->getData();
            $helper->saveDigitizedContract($file, $contract);

            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('account__show', ['id' => $account->getId()], 303);
        }

        return $this->render('account/contract/form.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }
}
