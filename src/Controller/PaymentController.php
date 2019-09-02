<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\PaymentInvoice;
use App\Entity\Person;
use App\Form\PaymentInvoiceReviewType;
use App\Form\PaymentInvoiceType;
use App\Form\PaymentSearchType;
use App\Helper\ProfileHelper;
use App\Repository\PaymentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @param Request $request
     * @param PaymentRepository $repository
     * @param ProfileHelper $profileHelper
     * @return Response
     * @Route("/pagamentos", name="payment__index")
     */
    public function index(Request $request, PaymentRepository $repository, ProfileHelper $profileHelper)
    {
        /** @var Person $user */
        $user = $this->getUser();
        $profile = $profileHelper->getCurrentProfile();

        $form = $this->createForm(PaymentSearchType::class);
        $form->handleRequest($request);

        if ($profile['id'] === ProfileHelper::PROFILE_STAKEHOLDER) {
            $accounts = $user->getAccounts();
            $provenance = Payment::PROVENANCE_CO_PARTICIPATION;
        }

        $payments = $repository->findUsingSearchForm($form, $accounts ?? null, $provenance ?? null);
        
        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Payment $payment
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     * @Route("/pagamentos/{id}/nota-fiscal", name="payment__invoice")
     */
    public function invoiceAdd(Payment $payment, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PaymentInvoiceType::class, $payment->getInvoice(), [
            'payment' => $payment,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($payment->getStatus() !== Payment::STATUS_WAITING_INVOICE) {
                return $this->redirectToRoute('payment__index', [], 303);
                throw new AccessDeniedHttpException();
            }

            /** @var PaymentInvoice $invoice */
            $invoice = $form->getData();

            if ($this->isGranted(["ROLE_ADMINISTRATIVE_ASSISTANT"])) {
                $invoice->setDateRevised(new DateTimeImmutable());
                $invoice->setRevisor($this->getUser());
                $invoice->setStatus(PaymentInvoice::STATUS_APPROVED);
            }

            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('payment__index', [], 303);
        }

        return $this->render('payment/invoice/form.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Payment $payment
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     * @Route("/pagamentos/{id}/nota-fiscal/revisao", name="payment__invoice__review")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function invoiceReview(Payment $payment, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PaymentInvoiceReviewType::class, $payment->getInvoice());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PaymentInvoice $invoice */
            $invoice = $form->getData();
            $invoice->setRevisor($this->getUser());
            $invoice->setDateRevised(new DateTimeImmutable());

            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('payment__invoice', ['id' => $payment->getId()], 303);
        }

        return $this->render('payment/invoice/review-form.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }
}
