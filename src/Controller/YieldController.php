<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\YieldWithdrawType;
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

class YieldController extends AbstractController
{
    /**
     * @param Contract $contract
     * @return BinaryFileResponse
     * @Route("/contratos-de-patrocinio/{id}/yield-withdraw", name="request__yield__withdraw")
     */
    public function registerYieldWithdraw(
        Contract $contract,
        Request $request,
        EntityManagerInterface $entityManager,
        ConfigurationRepository $crepository,
        TranslateRepository $transrepository
    ) {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        $form = $this->createForm(YieldWithdrawType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Adicionar o valor do yield para a table account
            $account = $contract->getAccount();
            $balance = number_format(($account->getBalance() + $contract->getYield()), 2, ".", "");
            $account->setBalance($balance);

            // Zerar a coluna yield na table contact
            $new_yield = "0.00";
            $contract->setYield($new_yield);
            $contract->setLast($contract->getValue());

            $entityManager->persist($contract);
            $entityManager->persist($account);
            $entityManager->flush();
        }

        $currency = $crepository->findOneByActive();

        return $this->render('contract/yield-withdraw.html.twig', [
            'translates' => $disableds,
            'contract' => $contract,
            'currency' => $currency->getLabel(),
            'form' => $form->createView(),
        ]);
    }
}
