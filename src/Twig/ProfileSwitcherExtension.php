<?php

namespace App\Twig;

use App\Helper\ProfileHelper;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class ProfileSwitcherExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var ProfileHelper
     */
    private $switcher;

    public function __construct(ProfileHelper $switcher)
    {
        $this->switcher = $switcher;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [
            'profileSwitcher' => $this->switcher,
        ];
    }
}
