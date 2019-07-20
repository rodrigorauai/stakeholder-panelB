<?php

namespace App\Controller;

use App\Helper\ProfileSwitcher;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @param ProfileSwitcher $profileSwitcher
     * @return RedirectResponse
     * @throws Exception
     * @Route("/", name="default")
     */
    public function index(ProfileSwitcher $profileSwitcher)
    {
        $currentProfile = $profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {

            case ProfileSwitcher::PROFILE_ADMINISTRATOR:
                return $this->redirectToRoute('withdraw__index');

            case ProfileSwitcher::PROFILE_STAKEHOLDER:
                return $this->redirectToRoute('dashboard');
        }

        throw new Exception(sprintf('Unable to handle current profile (%s)', $currentProfile['id']));
    }
}
