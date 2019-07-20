<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BallanceController extends AbstractController
{
    /**
     * @Route("/ballance", name="ballance")
     */
    public function index()
    {
        return $this->render('ballance/index.html.twig', [
            'controller_name' => 'BallanceController',
        ]);
    }
}
