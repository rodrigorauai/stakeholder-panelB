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
     * @var ProfileHelper
     */
    private $profileSwitcher;

    public function __construct(ProfileHelper $profileSwitcher)
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

            case ProfileHelper::PROFILE_ADMINISTRATOR:
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

            case ProfileHelper::PROFILE_STAKEHOLDER:
                return [
                    [
                        'route' => 'dashboard',
                        'icon'  => 'dashboard',
                        'label' => 'Dashboard',
                    ], [
                        'route' => 'payment__index',
                        'icon'  => 'attach_money',
                        'label' => 'Rendimentos',
                    ], [
                        'route' => 'withdraw__index',
                        'icon'  => 'save_alt',
                        'label' => 'Retiradas',
                    ], [
                        'route' => 'contract_index',
                        'icon'  => 'insert_drive_file',
                        'label' => 'Contratos',
                    ],
                ];
        }

        throw new Exception(sprintf('Unable to identify the user\'s current profile (%s)', $currentProfile['id']));
    }
}