<?php

namespace App\Twig;

use App\Helper\NavigationHelper;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class NavigationExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var NavigationHelper
     */
    private $navigationHelper;

    public function __construct(NavigationHelper $navigationHelper)
    {
        $this->navigationHelper = $navigationHelper;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [
            'navigation' => $this->navigationHelper,
        ];
    }
}
