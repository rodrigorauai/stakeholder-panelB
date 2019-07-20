<?php

namespace App\Helper;


use App\Entity\Person;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileSwitcher
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var string[]
     */
    private $profiles = [];

    public $availableProfiles = [
        [
            'label' => 'Patrocinador',
            'roles' => ['ROLE_USER', 'ROLE_STAKEHOLDER'],
        ], [
            'label' => 'Administrativo',
            'roles' => ['ROLE_ADMINISTRATIVE_ASSISTANT', 'ROLE_ADMINISTRATOR', 'ROLE_SYSTEM_ADMINISTRATOR'],
        ]
    ];

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function hasMultipleProfiles(): bool
    {
        return $this->getProfiles() > 1;
    }

    public function getProfiles(): array
    {
        if (empty($this->profiles)) {
            /** @var Person $user */
            $user = $this->tokenStorage->getToken()->getUser();

            foreach ($this->availableProfiles as $profile) {

                foreach ($profile['roles'] as $role) {

                    if (in_array($role, $user->getRoles())) {
                        $this->profiles[] = $profile['label'];
                        continue 2;
                    }

                }

            }

        }

        return $this->profiles;
    }
}