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
use App\Repository\PaymentRepository;
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

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @var string
     */
    protected $totalInvestment;

    /**
     * @var string
     */
    protected $totalCoParticipation;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ProfileSwitcher $profileSwitcher,
        ContractRepository $contractRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->profileSwitcher = $profileSwitcher;
        $this->contractRepository = $contractRepository;
        $this->tokenStorage = $tokenStorage;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @return bool|mixed
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getTotalInvestment()
    {
        if (!$this->totalInvestment) {

            $currentProfile = $this->profileSwitcher->getCurrentProfile();

            switch ($currentProfile['id']) {

                case ProfileSwitcher::PROFILE_ADMINISTRATOR:

                    break;

                case ProfileSwitcher::PROFILE_STAKEHOLDER:

                    /** @var Person $user */
                    $user = $this->tokenStorage->getToken()->getUser();

                    $accounts = [];
                    $accounts[] = $user->getAccount();

                    foreach ($user->getCompanies() as $company) {
                        $accounts[] = $company->getAccount();
                    }

                    $this->totalInvestment = $this->contractRepository->calculateTotalInvestment($accounts);
                    break;

                default:
                    throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));
            }
        }

        return $this->totalInvestment;
    }

    /**
     * @throws Exception
     */
    public function getTotalCoParticipation()
    {
        if (!$this->totalCoParticipation) {
            $currentProfile = $this->profileSwitcher->getCurrentProfile();

            switch ($currentProfile['id']) {
                case ProfileSwitcher::PROFILE_STAKEHOLDER:

                    /** @var Person $user */
                    $user = $this->tokenStorage->getToken()->getUser();

                    $accounts = [];
                    $accounts[] = $user->getAccount();

                    foreach ($user->getCompanies() as $company) {
                        $accounts[] = $company->getAccount();
                    }

                    $this->totalCoParticipation = $this->paymentRepository->calculateTotalCoParticipation($accounts);
                    break;

                default:
                    throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));
            }
        }

        return $this->totalCoParticipation;
    }

    /**
     * @return int|string
     * @throws NonUniqueResultException
     */
    public function getReturnRate()
    {
        if (!$this->getTotalInvestment()) {
            return 0;
        }

        $fraction = bcdiv($this->totalCoParticipation, $this->totalInvestment, 5);

        return bcmul($fraction, 100, 2);
    }
}