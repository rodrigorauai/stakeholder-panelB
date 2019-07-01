<?php

namespace App\Controller;

use App\Entity\Withdraw;
use App\Form\WithdrawReceiptType;
use App\Helper\UploadHelper;
use App\Repository\WithdrawRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WithdrawController extends AbstractController
{
    /**
     * @param WithdrawRepository $repository
     * @return Response
     * @Route("/retiradas", name="withdraw__index")
     */
    public function index(WithdrawRepository $repository)
    {
        $withdraws = $repository->findAll();

        return $this->render('withdraw/index.html.twig', [
            'withdraws' => $withdraws,
        ]);
    }

    /**
     * @param Withdraw $withdraw
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UploadHelper $uploadHelper
     * @return Response
     * @Route("/retiradas/{id}/registro-de-execucao", name="withdraw__register_execution")
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
            $uploadHelper->saveInvoice($file, $withdraw);

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
}
