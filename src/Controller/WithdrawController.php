<?php

namespace App\Controller;

use App\Repository\WithdrawRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}
