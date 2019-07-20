<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 07 2019
 */

namespace App\Helper;


use Exception;

class NavigationHelper
{
    /**
     * @var ProfileSwitcher
     */
    private $profileSwitcher;

    public function __construct(ProfileSwitcher $profileSwitcher)
    {
        $this->profileSwitcher = $profileSwitcher;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDrawerLinks()
    {
        $currentProfile = $this->profileSwitcher->getCurrentProfile();

        switch ($currentProfile['id']) {

            case ProfileSwitcher::PROFILE_ADMINISTRATOR:
                return [
                    [
                        'route' => 'withdraw__index',
                        'icon'  => 'attach_money',
                        'label' => 'Retiradas',
                    ], [
                        'route' => 'payment__index',
                        'icon'  => 'rotate_left',
                        'label' => 'Rendimentos',
                    ], [
                        'route' => 'stakehold_plan__index',
                        'icon'  => 'assignment',
                        'label' => 'Planos de Patrocínio',
                    ], [
                        'route' => 'person__index',
                        'icon'  => 'people',
                        'label' => 'Pessoas',
                    ], [
                        'route' => 'company__index',
                        'icon'  => 'store_mall_directory',
                        'label' => 'Empresas',
                    ],
                ];

            case ProfileSwitcher::PROFILE_STAKEHOLDER:
                return [
                    [
                        'route' => 'dashboard',
                        'icon'  => 'dashboard',
                        'label' => 'Dashboard',
                    ], [
                        'route' => 'dashboard', # TODO: Replace placeholder with the actual route
                        'icon'  => 'compare_arrows',
                        'label' => 'Extrato',
                    ], [
                        'route' => 'dashboard', # TODO: Replace placeholder with the actual route
                        'icon'  => 'insert_drive_file',
                        'label' => 'Contratos',
                    ],
                ];
        }

        throw new Exception(sprintf('Unable to identify the user\'s current profile (%s)', $currentProfile['id']));
    }
}