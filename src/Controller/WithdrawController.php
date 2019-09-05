<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Withdraw;
use App\Form\ReceiptFileType;
use App\Form\WithdrawReceiptType;
use App\Form\WithdrawSearchType;
use App\Helper\ProfileHelper;
use App\Helper\UploadHelper;
use App\Repository\WithdrawRepository;
use App\Repository\ConfigurationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WithdrawController extends AbstractController
{
    /**
     * @param Request $request
     * @param WithdrawRepository $repository
     * @param ProfileHelper $profileHelper
     * @return Response
     * @Route("/retiradas", name="withdraw__index")
     */
    public function index(Request $request, WithdrawRepository $repository, ProfileHelper $profileHelper, ConfigurationRepository $crepository)
    {
        /** @var Person $user */
        $user = $this->getUser();
        $profile = $profileHelper->getCurrentProfile();

        $form = $this->createForm(WithdrawSearchType::class);
        $form->handleRequest($request);

        if ($profile['id'] === ProfileHelper::PROFILE_STAKEHOLDER) {
            $accounts = $user->getAccounts();
        }

        $withdraws = $repository->findUsingSearchForm($form, $accounts ?? null);
        $currency = $crepository->findOneByActive();

        return $this->render('withdraw/index.html.twig', [
            'currency' => $currency->getLabel(),
            'withdraws' => $withdraws,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Withdraw $withdraw
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UploadHelper $uploadHelper
     * @return Response
     * @Route("/retiradas/{id}/registro-de-execucao", name="withdraw__register_execution")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function registerExecution(
        Withdraw $withdraw,
        Request $request,
        EntityManagerInterface $entityManager,
        UploadHelper $uploadHelper
    ) {
        $form = $this->createForm(WithdrawReceiptType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('receiptFile')->getData();
            $uploadHelper->saveReceipt($file, $withdraw);

            $withdraw->setExecuted($this->getUser());

            $entityManager->persist($withdraw);
            $entityManager->flush();

            return $this->redirectToRoute('withdraw__register_execution', ['id' => $withdraw->getId()], 303);
        }

        return $this->render('withdraw/execution/form.html.twig', [
            'withdraw' => $withdraw,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Withdraw $withdraw
     * @return BinaryFileResponse
     * @Route("/retiradas/{id}/comprovante-de-transferencia", name="withdraw__receipt__download")
     */
    public function downloadReceipt(Withdraw $withdraw)
    {
        if ($withdraw->getReceipts()->count() === 0) {
            throw new NotFoundHttpException();
        }

        return new BinaryFileResponse($withdraw->getReceipt()->getPath());
    }

    /**
     * @param Withdraw $withdraw
     * @param Request $request
     * @param UploadHelper $helper
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("/retiradas/{id}/comprovante-de-transferencia/editar", name="withdraw__receipt__add")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSSISTANT"})
     */
    public function addReceipt(
        Withdraw $withdraw,
        Request $request,
        UploadHelper $helper,
        EntityManagerInterface $entityManager
    ) {
        $form = $this->createForm(ReceiptFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $helper->saveReceipt($file, $withdraw);

            // Flushes receipt file entity
            $entityManager->flush();

            return $this->redirectToRoute('withdraw__index', [], 303);
        }

        return $this->render('withdraw/receipt/add.html.twig', [
            'withdraw' => $withdraw,
            'form' => $form->createView(),
        ]);
    }
}
