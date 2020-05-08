<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 07 2019
 */

namespace App\Helper;


use App\Entity\Payment;
use App\Entity\Person;
use App\Repository\ContractRepository;
use App\Repository\PaymentRepository;
use DateTime;
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
     * @var ProfileHelper
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

    /**
     * @var Payment
     */
    protected $nextPayment;

    /**
     * @var
     */
    protected $lastPayments;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ProfileHelper $profileSwitcher,
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
        // Valor Total de Investimento
        if (!$this->totalInvestment) {

            $currentProfile = $this->profileSwitcher->getCurrentProfile();

            switch ($currentProfile['id']) {

                case ProfileHelper::PROFILE_ADMINISTRATOR:

                    break;

                case ProfileHelper::PROFILE_STAKEHOLDER:

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
        // Rendimentos Acomulados
        if (!$this->totalCoParticipation) {
            $currentProfile = $this->profileSwitcher->getCurrentProfile();

            switch ($currentProfile['id']) {
                case ProfileHelper::PROFILE_STAKEHOLDER:

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
        // Retorno
        if (!$this->getTotalInvestment()) {
            return 0;
        }

        $fraction = bcdiv($this->totalCoParticipation, $this->totalInvestment, 5);

        return bcmul($fraction, 100, 2);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDataSetOfTotalReturnByDate()
    {
        // Rendimentos Totais Acumulados
        $currentProfile = $this->profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {
            case ProfileHelper::PROFILE_STAKEHOLDER:

                /** @var Person $user */
                $user = $this->tokenStorage->getToken()->getUser();

                $accounts = [];
                $accounts[] = $user->getAccount();

                foreach ($user->getCompanies() as $company) {
                    $accounts[] = $company->getAccount();
                }

                $coParticipations = $this->paymentRepository->findCoParticipationsByAccount($accounts);

                $dataSet = [];
                $firstMonth = null;
                $runningSum = '0.00';

                foreach ($coParticipations as $coParticipation) {

                    if ($firstMonth === null) {
                        $firstMonth = clone $coParticipation->getReward()->getDisclosureDate();
                    }

                    $runningSum = bcadd($runningSum, $coParticipation->getValue());

                    $dataSet[] = [
                        $coParticipation->getReward()->getDisclosureDate()->format('Y-m-d'),
                        (float) $runningSum,
                    ];
                }

                if (!$firstMonth) {
                    $firstMonth = new DateTime();
                }

                $initialDate = $firstMonth->modify('-1 month');

                array_unshift($dataSet, [
                    $initialDate->format('Y-m-d'),
                    0.00,
                ]);

                return $dataSet;

            default:
                throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDataSetOfReturnByDate()
    {
        // Rendimentos por Data
        $currentProfile = $this->profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {
            case ProfileHelper::PROFILE_STAKEHOLDER:

                /** @var Person $user */
                $user = $this->tokenStorage->getToken()->getUser();

                $accounts = [];
                $accounts[] = $user->getAccount();

                foreach ($user->getCompanies() as $company) {
                    $accounts[] = $company->getAccount();
                }

                $coParticipations = $this->paymentRepository->findCoParticipationsByAccount($accounts);

                $dataSet = [];
                $firstMonth = null;

                foreach ($coParticipations as $coParticipation) {

                    if ($firstMonth === null) {
                        $firstMonth = clone $coParticipation->getReward()->getDisclosureDate();
                    }

                    $dataSet[] = [
                        $coParticipation->getReward()->getDisclosureDate()->format('Y-m-d'),
                        (float) $coParticipation->getValue(),
                    ];
                }

                if (!$firstMonth) {
                    $firstMonth = new DateTime();
                }

                $initialDate = $firstMonth->modify('-1 month');

                array_unshift($dataSet, [
                    $initialDate->format('Y-m-d'),
                    0.00,
                ]);

                return $dataSet;

            default:
                throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));
        }
    }

    /**
     * @return Payment|array
     * @throws Exception
     */
    public function getNextPayment()
    {
        if (!$this->nextPayment) {
            $currentProfile = $this->profileSwitcher->getCurrentProfile();

            switch ($currentProfile['id']) {
                case ProfileHelper::PROFILE_STAKEHOLDER:

                    /** @var Person $user */
                    $user = $this->tokenStorage->getToken()->getUser();

                    $accounts = [];
                    $accounts[] = $user->getAccount();

                    foreach ($user->getCompanies() as $company) {
                        $accounts[] = $company->getAccount();
                    }

                    $coParticipations = $this->paymentRepository->findCoParticipationsByAccount($accounts);

                    $dataSet = [];
                    $firstMonth = null;

                    foreach ($coParticipations as $coParticipation) {

                        if ($firstMonth === null) {
                            $firstMonth = clone $coParticipation->getReward()->getPaymentDueDate();
                        }

                        $dataSet[] = [
                            $coParticipation->getReward()->getPaymentDueDate()->format('Y-m-d'),
                            (float)$coParticipation->getValue(),
                        ];
                    }

                    if (!$firstMonth) {
                        $firstMonth = new DateTime();
                    }

                    $initialDate = $firstMonth->modify('-1 month');

                    array_unshift($dataSet, [
                        $initialDate->format('Y-m-d'),
                        0.00,
                    ]);

                    $this->nextPayment = $this->paymentRepository->findNextPayment($accounts, true);
                    break;

                default:
                    throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));
            }
        }

        return $this->nextPayment;
    }

    /**
     * @throws Exception
     */
    public function getLastPayments()
    {
        if (!$this->lastPayments) {
            $this->lastPayments = $this->paymentRepository->findLastPayments($this->getCurrentAccounts(), 5);
        }

        return $this->lastPayments;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentAccounts()
    {
        $currentProfile = $this->profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {
            case ProfileHelper::PROFILE_STAKEHOLDER:

                /** @var Person $user */
                $user = $this->tokenStorage->getToken()->getUser();

                $accounts = [];
                $accounts[] = $user->getAccount();

                foreach ($user->getCompanies() as $company) {
                    $accounts[] = $company->getAccount();
                }

                return $accounts;

            default:
                throw new Exception(sprintf('Unable to handle profile %s', $currentProfile['id']));

        }
    }
}