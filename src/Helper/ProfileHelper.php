<?php

namespace App\Helper;


use App\Entity\Person;
use App\Form\ProfileSwitchType;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileHelper
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var string[]
     */
    private $profiles = [];

    const SESSION_CURRENT_PROFILE_KEY = 'profile_switcher__current_profile';

    const PROFILE_STAKEHOLDER = 'stakeholder';

    const PROFILE_ADMINISTRATOR = 'administrator';

    public $availableProfiles = [
         self::PROFILE_STAKEHOLDER => [
            'id'    => self::PROFILE_STAKEHOLDER,
            'label' => 'Sócio',
            'roles' => ['ROLE_USER', 'ROLE_STAKEHOLDER'],
        ],
        self::PROFILE_ADMINISTRATOR => [
            'id'    => self::PROFILE_ADMINISTRATOR,
            'label' => 'Administrativo',
            'roles' => ['ROLE_ADMINISTRATIVE_ASSISTANT', 'ROLE_ADMINISTRATOR', 'ROLE_SYSTEM_ADMINISTRATOR'],
        ]
    ];

    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        FormFactoryInterface $formFactory)
    {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->formFactory = $formFactory;
    }

    public function hasMultipleProfiles(): bool
    {
        return count($this->getProfiles()) > 1;
    }

    public function getProfiles(): array
    {
        if (empty($this->profiles)) {
            /** @var Person $user */
            $user = $this->tokenStorage->getToken()->getUser();

            foreach ($this->availableProfiles as $profile) {

                foreach ($profile['roles'] as $role) {

                    if (in_array($role, $user->getRoles())) {
                        $this->profiles[] = $profile;
                        continue 2;
                    }

                }

            }

        }

        return $this->profiles;
    }

    public function getCurrentProfile(): array
    {
        $profile = $this->session->get(self::SESSION_CURRENT_PROFILE_KEY, 'stakeholder');

        return $this->availableProfiles[$profile];
    }

    /**
     * @param string $profile
     * @throws Exception
     */
    public function setCurrentProfile(string $profile)
    {
        if (false === array_key_exists($profile, $this->availableProfiles)) {
            throw new Exception(sprintf('%s profile does not exist.', $profile));
        }

        $this->session->set(self::SESSION_CURRENT_PROFILE_KEY, $profile);
    }

    public function getFormView()
    {
        $form = $this->formFactory->create(ProfileSwitchType::class, [
            'activeProfile' => $this->getCurrentProfile()['id'],
        ]);

        return $form->createView();
    }

    public function isAccessingAsAdministrator(): bool
    {
        return ($this->getCurrentProfile()['id'] === self::PROFILE_ADMINISTRATOR);
    }

    public function isAccessingAsStakeholder(): bool
    {
        return ($this->getCurrentProfile()['id'] === self::PROFILE_STAKEHOLDER);
    }
}