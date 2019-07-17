<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentInvoiceType;
use App\Form\PaymentSearchType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @param PaymentRepository $repository
     * @return Response
     * @Route("/pagamentos", name="payment__index")
     */
    public function index(Request $request, PaymentRepository $repository)
    {
        $payments = $repository->findAll();

        $form = $this->createForm(PaymentSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payments = $repository->findByExampleField($form->getData('index'));
        }
        
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
     * @Route("/pagamentos/{id}/nota-fiscal", name="payment__invoice__edit")
     */
    public function invoiceEdit(Payment $payment, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PaymentInvoiceType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('payment__invoice__edit', ['id' => $payment->getId()], 303);
        }

        return $this->render('payment/invoice/edit.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }
}
