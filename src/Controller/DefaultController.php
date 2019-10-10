<?php

namespace App\Controller;

use App\Helper\ProfileHelper;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @param ProfileHelper $profileSwitcher
     * @return RedirectResponse
     * @throws Exception
     * @Route("/", name="default")
     */
    public function index(ProfileHelper $profileSwitcher)
    {
        $currentProfile = $profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {

            case ProfileHelper::PROFILE_ADMINISTRATOR:
                return $this->redirectToRoute('withdraw__index');

            case ProfileHelper::PROFILE_STAKEHOLDER:
                return $this->redirectToRoute('dashboard');
        }

        throw new Exception(sprintf('Unable to handle current profile (%s)', $currentProfile['id']));
    }
}
