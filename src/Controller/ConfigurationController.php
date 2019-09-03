<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationController extends AbstractController
{
    /**
     * @return Response
     * @Route("/configuration", name="configuration")
     */
    public function index()
    {
        return $this->render('configuration/index.html.twig', [
        ]);
    }
}
