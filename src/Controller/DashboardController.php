<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use App\Repository\ConfigurationRepository;
use App\Repository\TranslateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @param DashboardHelper $dashboardHelper
     * @param ConfigurationRepository $repository
     * @param TranslateRepository $transrepository
     * @return Response
     * @Route("/dashboard", name="dashboard")
     */
    public function index(DashboardHelper $dashboardHelper, ConfigurationRepository $repository, TranslateRepository $transrepository)
    {
        $currency = $repository->findOneByActive();


        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        return $this->render('dashboard/index.html.twig', [
            'translates' => $disableds,
            'currency' => $currency->getLabel(),
            'dashboardHelper' => $dashboardHelper,
        ]);
    }
}
