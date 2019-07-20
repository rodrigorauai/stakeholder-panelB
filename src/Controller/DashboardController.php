<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @param DashboardHelper $dashboardHelper
     * @return Response
     * @Route("/dashboard", name="dashboard")
     */
    public function index(DashboardHelper $dashboardHelper)
    {
        return $this->render('dashboard/index.html.twig', [
            'dashboardHelper' => $dashboardHelper,
        ]);
    }
}
