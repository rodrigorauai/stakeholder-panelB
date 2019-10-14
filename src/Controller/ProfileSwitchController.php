<?php

namespace App\Controller;

use App\Form\ProfileSwitchType;
use App\Helper\ProfileHelper;
use App\Repository\TranslateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileSwitchController extends AbstractController
{
    /**
     * @param Request $request
     * @param ProfileHelper $profileSwitcher
     * @return RedirectResponse|Response
     * @throws \Exception
     * @Route("/profile/switch", name="profile__switch")
     */
    public function index(Request $request, ProfileHelper $profileSwitcher)
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

    /**
     * @param TranslateRepository $transrepository
     * @return Response
     */
    public function editTranslate(TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        return $this->render('_components/drawer/profile-switcher--dialog.html.twig', [
            'translates' => $disableds,
        ]);
    }

    /**
     * @param TranslateRepository $transrepository
     * @return Response
     */
    public function editTranslateUSN(TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        return $this->render('_components/drawer/drawer.html.twig', [
            'translates' => $disableds,
        ]);
    }

}
