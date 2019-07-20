<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 07 2019
 */

namespace App\Helper;


use App\Entity\Person;
use App\Repository\ContractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DashboardHelper
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ProfileSwitcher
     */
    private $profileSwitcher;

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ProfileSwitcher $profileSwitcher,
        ContractRepository $contractRepository)
    {
        $this->profileSwitcher = $profileSwitcher;
        $this->contractRepository = $contractRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return bool|mixed
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getTotalInvestment()
    {
        $currentProfile = $this->profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {

            case ProfileSwitcher::PROFILE_ADMINISTRATOR:

                return false;

            case ProfileSwitcher::PROFILE_STAKEHOLDER:

                /** @var Person $user */
                $user = $this->tokenStorage->getToken()->getUser();

                $accounts = [];
                $accounts[] = $user->getAccount();

                foreach ($user->getCompanies() as $company) {
                    $accounts[] = $company->getAccount();
                }

                $sum = $this->contractRepository->calculateTotalInvestment($accounts);

                return $sum;

        }

        throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));
    }
}