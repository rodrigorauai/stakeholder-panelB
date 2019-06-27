<?php

namespace App\Controller;

use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @param PaymentRepository $repository
     * @return Response
     * @Route("/pagamentos", name="payment__index")
     */
    public function index(PaymentRepository $repository)
    {
        $payments = $repository->findAll();

        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
        ]);
    }
}
