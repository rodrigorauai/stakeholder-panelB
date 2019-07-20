<?php

namespace App\Controller;

use App\Form\ProfileSwitchType;
use App\Helper\ProfileSwitcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileSwitchController extends AbstractController
{
    /**
     * @param Request $request
     * @param ProfileSwitcher $profileSwitcher
     * @return RedirectResponse|Response
     * @throws \Exception
     * @Route("/profile/switch", name="profile__switch")
     */
    public function index(Request $request, ProfileSwitcher $profileSwitcher)
    {
        $form = $this->createForm(ProfileSwitchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profileSwitcher->setCurrentProfile($form->get('activeProfile')->getData());

            return $this->redirectToRoute('default');
        }

        return $this->render('account_switch/index.html.twig', [
            'controller_name' => 'AccountSwitchController',
        ]);
    }
}
