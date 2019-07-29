<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use App\Helper\ProfileHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @param DashboardHelper $dashboardHelper
     * @return Response
     * @Route("/dashboard", name="dashboard")
     * @IsGranted({"ROLE_STAKEHOLDER"})
     */
    public function index(ProfileHelper $profileSwitcher, DashboardHelper $dashboardHelper)
    {
        $currentProfile = $profileSwitcher->getCurrentProfile();
        switch ($currentProfile['id']) {
            case ProfileHelper::PROFILE_ADMINISTRATOR:
                return $this->redirectToRoute('withdraw__index');
        }

        return $this->render('dashboard/index.html.twig', [
            'dashboardHelper' => $dashboardHelper,
        ]);
    }
}
