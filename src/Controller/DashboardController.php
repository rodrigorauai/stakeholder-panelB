<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use App\Repository\ConfigurationRepository;
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
    public function index(DashboardHelper $dashboardHelper, ConfigurationRepository $repository)
    {
        $currency = $repository->findOneByActive();

        return $this->render('dashboard/index.html.twig', [
            'currency' => $currency->getLabel(),
            'dashboardHelper' => $dashboardHelper,
        ]);
    }
}
